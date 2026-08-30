@extends('layouts.app')

@section('title', 'HR Management - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto flex flex-col gap-6 w-full pb-12">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 flex items-start gap-3 mb-4 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-error-container border border-error/20 flex items-start gap-3 mb-4 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Organization</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1">HR Management</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola staf, pantau pengeluaran gaji, dan administrasi berkas secara aman.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
            <div class="relative">
                <select class="appearance-none bg-surface-container-low border border-outline-variant rounded-full px-4 py-2 pr-10 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-on-surface cursor-pointer">
                    <option>Januari</option>
                    <option>Februari</option>
                    <option>Maret</option>
                    <option selected="">April</option>
                    <option>Mei</option>
                    <option>Juni</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 18px;">expand_more</span>
            </div>
            <div class="relative">
                <select class="appearance-none bg-surface-container-low border border-outline-variant rounded-full px-4 py-2 pr-10 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-on-surface cursor-pointer">
                    <option>2022</option>
                    <option>2023</option>
                    <option selected="">2024</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 18px;">expand_more</span>
            </div>
            <button onclick="openAddModal()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Input Karyawan Baru
            </button>
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Employees Table (Spans 8 columns) -->
        <div class="lg:col-span-8 bg-surface-container-lowest rounded-2xl border border-outline-variant flex flex-col h-full shadow-sm">
            <div class="p-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/40 rounded-t-2xl">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">group</span>
                    Daftar Karyawan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/10 border-b border-outline-variant/60 font-label-caps text-label-caps text-on-surface-variant">
                            <th class="py-3 px-5 font-bold">Nama Karyawan</th>
                            <th class="py-3 px-5 font-bold">Jabatan</th>
                            <th class="py-3 px-5 font-bold">Status Kontrak</th>
                            <th class="py-3 px-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/60 font-body-sm">
                        @foreach($employees as $emp)
                        <tr class="group/row hover:bg-surface-container-low/30 transition-all duration-200">
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full overflow-hidden bg-primary-container shrink-0 border border-outline-variant shadow-sm transition-all duration-300 group-hover/row:scale-105">
                                        <img class="w-full h-full object-cover" alt="Karyawan Avatar" src="{{ $emp->avatar }}"/>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-body-md font-semibold text-primary truncate hover:text-indigo-600 transition-colors">{{ $emp->name }}</span>
                                        <span class="font-data-mono text-[10px] text-on-surface-variant/75 mt-0.5">ID: {{ $emp->employee_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-5 text-on-surface-variant font-body-md">{{ $emp->position }}</td>
                            <td class="py-3 px-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold {{ str_contains(strtolower($emp->contract_status), 'permanent') ? 'bg-green-500/10 text-green-700' : 'bg-surface-variant text-on-surface-variant' }}">{{ $emp->contract_status }}</span>
                            </td>
                            <td class="py-3 px-5 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <button onclick="openEditModal({{ json_encode($emp) }})" class="p-2 rounded-xl border border-outline-variant hover:border-indigo-600 hover:bg-indigo-50/50 text-on-surface-variant hover:text-indigo-600 transition-all duration-200 shadow-sm" title="Edit Karyawan">
                                        <span class="material-symbols-outlined text-[18px] block">edit</span>
                                    </button>
                                    <form action="{{ route('hr.destroy', $emp->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus data karyawan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl border border-outline-variant hover:border-error hover:bg-error/5 text-on-surface-variant hover:text-error transition-all duration-200 shadow-sm" title="Hapus Karyawan">
                                            <span class="material-symbols-outlined text-[18px] block">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($employees->isEmpty())
                        <tr>
                            <td colspan="4" class="py-12 text-center text-on-surface-variant font-body-md">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">face</span>
                                    <p>Tidak ada karyawan ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-4 border-t border-outline-variant bg-surface-container-low/40 flex justify-between items-center text-on-surface-variant font-body-sm rounded-b-2xl">
                <span>Menampilkan {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} dari {{ $employees->total() }} karyawan</span>
                <div class="flex gap-2">
                    @if($employees->onFirstPage())
                        <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $employees->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Sebelumnya</a>
                    @endif

                    @if($employees->hasMorePages())
                        <a href="{{ $employees->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Selanjutnya</a>
                    @else
                        <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Selanjutnya</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Side Panel: Payroll & Leave (Spans 4 columns) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Payroll Summary -->
            <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg transition-all duration-300 overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-500/5 to-transparent rounded-bl-full"></div>
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-indigo-600">account_balance_wallet</span>
                    Payroll Summary
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/60 font-body-sm">
                        <span class="text-on-surface-variant font-medium">Total Gaji Bulan Ini</span>
                        <span class="font-data-mono text-data-mono font-bold text-primary text-base">Rp {{ number_format($totalSalary, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/60 font-body-sm">
                        <span class="text-on-surface-variant font-medium">Estimasi Bonus</span>
                        <span class="font-data-mono text-data-mono font-bold text-emerald-600 text-base">Rp {{ number_format($estimatedBonus, 0, ',', '.') }}</span>
                    </div>
                    <form action="{{ route('hr.payroll') }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin memproses payroll bulan ini sebesar Rp {{ number_format($totalSalary, 0, ',', '.') }}?');">
                        @csrf
                        <button type="submit" class="w-full mt-2 border border-outline-variant hover:border-indigo-600 text-primary hover:text-indigo-600 hover:bg-indigo-50/10 font-body-sm font-semibold py-2.5 rounded-full transition-all shadow-sm">
                            Proses Payroll
                        </button>
                    </form>
                </div>
            </div>

            <!-- Spend Chart -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-indigo-600">trending_up</span>
                    Tren Pengeluaran Gaji
                </h3>
                <div class="h-32 flex items-end gap-2 px-2">
                    <div class="flex-1 bg-indigo-200/50 rounded-t-lg h-[40%] relative group hover:bg-indigo-600 transition-all duration-300 cursor-default">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">380M</div>
                    </div>
                    <div class="flex-1 bg-indigo-300/50 rounded-t-lg h-[55%] relative group hover:bg-indigo-600 transition-all duration-300 cursor-default">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">395M</div>
                    </div>
                    <div class="flex-1 bg-indigo-200/50 rounded-t-lg h-[65%] relative group hover:bg-indigo-600 transition-all duration-300 cursor-default">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">410M</div>
                    </div>
                    <div class="flex-1 bg-indigo-100/50 rounded-t-lg h-[60%] relative group hover:bg-indigo-600 transition-all duration-300 cursor-default">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">405M</div>
                    </div>
                    <div class="flex-1 bg-indigo-300/50 rounded-t-lg h-[75%] relative group hover:bg-indigo-600 transition-all duration-300 cursor-default">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">415M</div>
                    </div>
                    <div class="flex-1 bg-indigo-600 rounded-t-lg h-[85%] relative group hover:bg-indigo-700 transition-all duration-300 cursor-default animate-pulse">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-data-mono bg-primary text-on-primary px-1.5 py-0.5 rounded shadow whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">425M</div>
                    </div>
                </div>
                <div class="flex justify-between mt-2.5 px-1">
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Nov</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Des</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Jan</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Feb</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Mar</span>
                    <span class="text-[10px] text-indigo-600 font-bold font-label-caps">Apr</span>
                </div>
            </div>

            <!-- Leave Management -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 flex-1 shadow-sm flex flex-col justify-between gap-4">
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-indigo-600">event_note</span>
                        Manajemen Cuti
                    </h3>
                    <div class="space-y-4 font-body-sm">
                        <div class="p-3 bg-rose-500/10 rounded-2xl border border-rose-100 flex items-start gap-3">
                            <span class="material-symbols-outlined text-error mt-0.5">pending_actions</span>
                            <div>
                                <p class="font-body-sm font-bold text-on-surface">{{ $pendingLeavesCount }} Pengajuan Menunggu</p>
                                <p class="font-body-sm text-on-surface-variant text-[11px] mt-0.5">Perlu approval dari HR Manager</p>
                            </div>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Sisa Cuti Terendah</p>
                            <ul class="space-y-2">
                                @foreach($lowestLeaveEmployees as $le)
                                <li class="flex justify-between text-body-sm items-center py-1 border-b border-outline-variant/30 last:border-none">
                                    <span class="text-on-surface font-medium">{{ $le->name }}</span>
                                    <span class="font-data-mono {{ $le->leave_balance <= 3 ? 'text-error font-bold' : 'text-on-surface-variant font-bold' }}">{{ $le->leave_balance }} Hari</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <button onclick="openLeavesModal()" class="w-full text-indigo-600 hover:text-indigo-700 font-bold py-2 border border-outline-variant rounded-full text-center font-label-caps text-label-caps hover:bg-indigo-50/10 transition-all shadow-sm">
                    Lihat Semua Pengajuan
                </button>
            </div>
        </div>
    </div>

    <!-- Digital Documents Section (Bottom Full Width) -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-6 shadow-sm">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-outline-variant/60">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">description</span>
                Dokumen HR (Surat Kontrak)
            </h3>
            <button onclick="openUploadModal()" aria-label="Upload" class="text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50/20 px-4 py-2 rounded-full border border-outline-variant hover:border-indigo-600 transition-all flex items-center gap-1.5 font-body-sm font-semibold shadow-sm">
                <span class="material-symbols-outlined" style="font-size: 18px;">upload_file</span> Upload Dokumen
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($documents as $doc)
            <!-- Doc Card -->
            <div class="flex items-center p-4 border border-outline-variant rounded-2xl hover:border-indigo-600 hover:shadow-md transition-all duration-300 cursor-pointer group bg-surface-container-lowest relative">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 mr-4 shrink-0 transition-transform duration-300 group-hover:scale-105">
                    <span class="material-symbols-outlined">contract</span>
                </div>
                <div class="flex-1 min-w-0 pr-8">
                    <p class="font-body-sm font-bold text-on-surface truncate group-hover:text-indigo-600 transition-colors">{{ $doc->filename }}</p>
                    <p class="font-body-sm text-on-surface-variant text-[11px] mt-0.5">Diperbarui: {{ $doc->updated_at->format('d M Y') }} • {{ $doc->file_size }}</p>
                </div>
                <form action="{{ route('hr.documents.destroy', $doc->id) }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus dokumen ini?');" class="absolute right-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-on-surface-variant/60 hover:text-error p-1.5 hover:bg-rose-50 rounded-lg transition-colors">
                        <span class="material-symbols-outlined block" style="font-size: 20px;">delete</span>
                    </button>
                </form>
            </div>
            @endforeach

            @if($documents->isEmpty())
            <div class="col-span-3 py-12 text-center text-on-surface-variant font-body-md">
                <div class="flex flex-col items-center justify-center">
                    <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">folder_open</span>
                    <p>Tidak ada dokumen kontrak ditemukan.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">person_add</span>
                Input Karyawan Baru
            </h3>
            <button onclick="closeAddModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('hr.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Sarah Johnson"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Jabatan</label>
                <input type="text" name="position" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Head of Piano Dept."/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Status Kontrak</label>
                <select name="contract_status" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    <option value="Permanent">Permanent</option>
                    <option value="Contract (6 Mo)">Contract (6 Mo)</option>
                    <option value="Contract (1 Yr)">Contract (1 Yr)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Gaji (Rp)</label>
                    <input type="number" name="salary" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: 15000000"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Sisa Jatah Cuti</label>
                    <input type="number" name="leave_balance" min="0" required value="12" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">edit_note</span>
                Edit Karyawan
            </h3>
            <button onclick="closeEditModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Nama Lengkap</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Jabatan</label>
                <input type="text" id="edit-position" name="position" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Status Kontrak</label>
                <select id="edit-contract-status" name="contract_status" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    <option value="Permanent">Permanent</option>
                    <option value="Contract (6 Mo)">Contract (6 Mo)</option>
                    <option value="Contract (1 Yr)">Contract (1 Yr)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Gaji (Rp)</label>
                    <input type="number" id="edit-salary" name="salary" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Sisa Jatah Cuti</label>
                    <input type="number" id="edit-leave-balance" name="leave_balance" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Ganti Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Leaves Modal -->
<div id="leaves-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-lg shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">event_note</span>
                Daftar Pengajuan Cuti
            </h3>
            <button onclick="closeLeavesModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
            @foreach($pendingLeaves as $pl)
            <div class="p-3.5 border border-outline-variant rounded-2xl flex justify-between items-center bg-surface-container-low font-body-sm hover:shadow transition-all duration-200">
                <div>
                    <p class="font-bold text-primary text-sm">{{ $pl->employee_name }}</p>
                    <p class="text-on-surface-variant text-[11px] mt-0.5">{{ $pl->leave_type }} • {{ $pl->duration }} Hari</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('hr.leaves.approve', $pl->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white rounded-full text-xs font-semibold hover:bg-indigo-700 transition-colors shadow-sm">Setujui</button>
                    </form>
                    <form action="{{ route('hr.leaves.reject', $pl->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-1.5 bg-rose-600 text-white rounded-full text-xs font-semibold hover:bg-rose-700 transition-colors shadow-sm">Tolak</button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($pendingLeaves->isEmpty())
            <p class="text-center text-on-surface-variant py-8 text-sm">Tidak ada pengajuan cuti menunggu.</p>
            @endif
        </div>
        <div class="flex justify-end pt-4 border-t border-outline-variant mt-4">
            <button onclick="closeLeavesModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="upload-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">upload_file</span>
                Upload Dokumen Kontrak
            </h3>
            <button onclick="closeUploadModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('hr.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">File Dokumen (PDF, DOC, DOCX)</label>
                <input type="file" name="document_file" required accept=".pdf,.doc,.docx" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeUploadModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl text-center font-body-sm animate-scale-up">
        <div class="w-14 h-14 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[32px]">delete_forever</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi</h3>
        <p id="confirm-message" class="text-on-surface-variant mb-6 text-sm">Apakah Anda yakin?</p>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
            <button id="confirm-submit-btn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Proses</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let formToSubmit = null;
    function showCustomConfirm(form, message) {
        formToSubmit = form;
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-modal').classList.remove('hidden');
    }
    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        formToSubmit = null;
    }
    document.getElementById('confirm-submit-btn').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    function openAddModal() {
        document.getElementById('add-modal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
    }
    function openEditModal(emp) {
        document.getElementById('edit-form').action = '/hr/' + emp.id;
        document.getElementById('edit-name').value = emp.name;
        document.getElementById('edit-position').value = emp.position;
        document.getElementById('edit-contract-status').value = emp.contract_status;
        document.getElementById('edit-salary').value = emp.salary;
        document.getElementById('edit-leave-balance').value = emp.leave_balance;
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    function openLeavesModal() {
        document.getElementById('leaves-modal').classList.remove('hidden');
    }
    function closeLeavesModal() {
        document.getElementById('leaves-modal').classList.add('hidden');
    }

    function openUploadModal() {
        document.getElementById('upload-modal').classList.remove('hidden');
    }
    function closeUploadModal() {
        document.getElementById('upload-modal').classList.add('hidden');
    }
</script>
@endpush
