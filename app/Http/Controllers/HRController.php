<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HrLeave;
use App\Models\HrDocument;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HRController extends Controller
{
    /**
     * Display filtered and paginated list of employees along with payroll/leave summaries.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search by name or employee_id
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Paginate list
        $employees = $query->orderBy('name')->paginate(10);

        // Fetch dynamic payroll stats
        $allEmployees = Employee::all();
        $totalSalary = $allEmployees->sum('salary');
        $estimatedBonus = $totalSalary * 0.0835; // Matches approx 35.5M seeder placeholder at 425M base

        // Fetch lowest leave balances from employees table
        $lowestLeaveEmployees = Employee::orderBy('leave_balance')->take(5)->get();

        // Fetch pending leave applications
        $pendingLeaves = HrLeave::where('status', 'Menunggu')->get();
        $pendingLeavesCount = $pendingLeaves->count();

        // Fetch documents
        $documents = HrDocument::orderBy('created_at', 'desc')->get();

        return view('hr', compact(
            'employees',
            'totalSalary',
            'estimatedBonus',
            'lowestLeaveEmployees',
            'pendingLeaves',
            'pendingLeavesCount',
            'documents'
        ));
    }

    /**
     * Store new employee record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contract_status' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'leave_balance' => 'required|integer|min:0',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Generate unique employee ID (EMP-xxx)
        do {
            $randomNum = mt_rand(100, 999);
            $empId = 'EMP-' . $randomNum;
        } while (Employee::where('employee_id', $empId)->exists());

        $validated['employee_id'] = $empId;

        // Process file upload if present
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $validated['avatar'] = '/uploads/avatars/' . $filename;
        } else {
            $validated['avatar'] = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80';
        }

        Employee::create($validated);

        return redirect()->route('hr')->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Update existing employee.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'contract_status' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'leave_balance' => 'required|integer|min:0',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Process file upload if present
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $validated['avatar'] = '/uploads/avatars/' . $filename;
        } else {
            // Retain existing avatar if no new file is uploaded
            unset($validated['avatar']);
        }

        $employee->update($validated);

        return redirect()->route('hr')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Delete employee.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('hr')->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Process payroll: logs monthly salaries as expense under Finance ledger.
     */
    public function processPayroll(Request $request)
    {
        $allEmployees = Employee::all();
        $totalSalary = $allEmployees->sum('salary');

        if ($totalSalary <= 0) {
            return redirect()->route('hr')->withErrors(['error' => 'Tidak ada nominal gaji karyawan yang dapat diproses.']);
        }

        // Record monthly payroll as expense in Finance ledger
        do {
            $trxId = 'TRX-' . mt_rand(10000, 99999);
        } while (Transaction::where('transaction_id', $trxId)->exists());

        Transaction::create([
            'transaction_id' => $trxId,
            'type' => 'expense',
            'description' => 'Payroll Gaji Karyawan - ' . date('F Y'),
            'category' => 'Operasional',
            'amount' => $totalSalary,
            'date' => date('Y-m-d'),
        ]);

        return redirect()->route('hr')->with('success', 'Payroll berhasil diproses sebesar Rp ' . number_format($totalSalary, 0, ',', '.') . ' dan tercatat di modul Finance.');
    }

    /**
     * Approve leave request: deduct leave duration from employee sisa cuti.
     */
    public function approveLeave($id)
    {
        $leave = HrLeave::findOrFail($id);

        // Find employee by name
        $employee = Employee::where('name', $leave->employee_name)->first();
        if ($employee) {
            $employee->leave_balance = max(0, $employee->leave_balance - $leave->duration);
            $employee->save();
        }

        $leave->status = 'Disetujui';
        $leave->save();

        return redirect()->route('hr')->with('success', 'Pengajuan cuti ' . $leave->employee_name . ' berhasil disetujui.');
    }

    /**
     * Reject leave request.
     */
    public function rejectLeave($id)
    {
        $leave = HrLeave::findOrFail($id);
        $leave->status = 'Ditolak';
        $leave->save();

        return redirect()->route('hr')->with('success', 'Pengajuan cuti ' . $leave->employee_name . ' telah ditolak.');
    }

    /**
     * Upload PDF HR contract document.
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = $file->getClientOriginalName();
            
            // Check if document with same filename already exists
            if (HrDocument::where('filename', $filename)->exists()) {
                return redirect()->route('hr')->withErrors(['error' => 'Dokumen dengan nama yang sama sudah ada.']);
            }

            $fileSizeInBytes = $file->getSize();
            // Format size
            if ($fileSizeInBytes >= 1048576) {
                $fileSize = round($fileSizeInBytes / 1048576, 1) . ' MB';
            } else {
                $fileSize = round($fileSizeInBytes / 1024, 0) . ' KB';
            }

            $file->move(public_path('uploads/documents'), $filename);

            HrDocument::create([
                'filename' => $filename,
                'file_path' => '/uploads/documents/' . $filename,
                'file_size' => $fileSize,
            ]);

            return redirect()->route('hr')->with('success', 'Dokumen kontrak baru berhasil diupload.');
        }

        return redirect()->route('hr')->withErrors(['error' => 'Gagal mengupload dokumen.']);
    }

    /**
     * Delete contract document.
     */
    public function deleteDocument($id)
    {
        $doc = HrDocument::findOrFail($id);
        
        // Remove file from local path
        $filePath = public_path($doc->file_path);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $doc->delete();

        return redirect()->route('hr')->with('success', 'Dokumen kontrak berhasil dihapus.');
    }
}
