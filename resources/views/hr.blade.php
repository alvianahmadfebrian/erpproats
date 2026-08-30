@extends('layouts.app')

@section('title', 'HR Management - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto flex flex-col gap-8 w-full">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-stack-sm rounded bg-green-50 border border-green-200 flex items-start gap-2 mb-unit">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-stack-sm rounded bg-error-container border border-error/20 flex items-start gap-2 mb-unit">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-display-lg text-display-lg text-primary">HR Management</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Overview of employees, payroll, and documents.</p>
        </div>
        <div class="flex items-center gap-3 mt-4 sm:mt-0">
            <div class="relative">
                <select class="appearance-none bg-surface-container-low border border-outline-variant rounded px-4 py-2 pr-10 text-body-sm font-body-sm focus:outline-none focus:ring-2 focus:ring-secondary-container text-on-surface cursor-pointer">
                    <option>Januari</option>
                    <option>Februari</option>
                    <option>Maret</option>
                    <option selected="">April</option>
                    <option>Mei</option>
                    <option>Juni</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" data-icon="expand_more">expand_more</span>
            </div>
            <div class="relative">
                <select class="appearance-none bg-surface-container-low border border-outline-variant rounded px-4 py-2 pr-10 text-body-sm font-body-sm focus:outline-none focus:ring-2 focus:ring-secondary-container text-on-surface cursor-pointer">
                    <option>2022</option>
                    <option>2023</option>
                    <option selected="">2024</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" data-icon="expand_more">expand_more</span>
            </div>
            <button onclick="openAddModal()" class="bg-primary text-on-primary px-6 py-2.5 rounded font-body-sm font-bold flex items-center gap-2 hover:bg-surface-tint transition-colors">
                <span class="material-symbols-outlined text-[18px]" data-icon="person_add">person_add</span>
                Input Karyawan Baru
            </button>
        </div>
    </div>
    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Employees Table (Spans 8 columns) -->
        <div class="lg:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant flex flex-col h-full shadow-sm">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant" data-icon="group">group</span>
                    Daftar Karyawan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant font-label-caps text-label-caps text-on-surface-variant">
                            <th class="p-4 font-bold">Nama Karyawan</th>
                            <th class="p-4 font-bold">Jabatan</th>
                            <th class="p-4 font-bold">Status Kontrak</th>
                            <th class="p-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-sm">
                        @foreach($employees as $emp)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors h-[64px]">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-container shrink-0">
                                        <img class="w-full h-full object-cover" alt="Karyawan Avatar" src="{{ $emp->avatar }}"/>
                                    </div>
                                    <div>
                                        <div class="font-body-md text-body-md font-semibold text-primary">{{ $emp->name }}</div>
                                        <div class="font-data-mono text-data-mono text-on-surface-variant text-[11px]">ID: {{ $emp->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface-variant">{{ $emp->position }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps {{ str_contains(strtolower($emp->contract_status), 'permanent') ? 'bg-secondary-fixed text-on-secondary-fixed' : 'bg-surface-variant text-on-surface-variant' }} font-bold">{{ $emp->contract_status }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <button onclick="openEditModal({{ json_encode($emp) }})" class="p-1 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors" title="Edit Karyawan">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form action="{{ route('hr.destroy', $emp->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus data karyawan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-surface-container-high text-error transition-colors" title="Hapus Karyawan">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($employees->isEmpty())
                        <tr>
                            <td colspan="4" class="p-8 text-center text-on-surface-variant">Tidak ada karyawan ditemukan.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-3 border-t border-outline-variant bg-surface-bright flex justify-between items-center text-on-surface-variant font-body-sm rounded-b-xl">
                <span>Menampilkan {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} dari {{ $employees->total() }} karyawan</span>
                <div class="flex gap-2">
                    @if($employees->onFirstPage())
                        <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Sebelumnya</button>
                    @else
                        <a href="{{ $employees->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Sebelumnya</a>
                    @endif

                    @if($employees->hasMorePages())
                        <a href="{{ $employees->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Selanjutnya</a>
                    @else
                        <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Selanjutnya</button>
                    @endif
                </div>
            </div>
        </div>
        <!-- Side Panel: Payroll & Leave (Spans 4 columns) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Payroll Summary -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant" data-icon="account_balance_wallet">account_balance_wallet</span>
                    Payroll Summary
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-surface-variant font-body-sm">
                        <span class="text-on-surface-variant">Total Gaji Bulan Ini</span>
                        <span class="font-data-mono text-data-mono font-bold text-primary">Rp {{ number_format($totalSalary, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-surface-variant font-body-sm">
                        <span class="text-on-surface-variant">Estimasi Bonus</span>
                        <span class="font-data-mono text-data-mono font-bold text-secondary">Rp {{ number_format($estimatedBonus, 0, ',', '.') }}</span>
                    </div>
                    <form action="{{ route('hr.payroll') }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin memproses payroll bulan ini sebesar Rp {{ number_format($totalSalary, 0, ',', '.') }}?');">
                        @csrf
                        <button type="submit" class="w-full mt-2 bg-transparent border border-outline text-primary font-body-sm font-bold py-2 rounded hover:bg-surface-container-low transition-colors">
                            Proses Payroll
                        </button>
                    </form>
                </div>
            </div>
            <!-- Spend Chart -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant" data-icon="trending_up">trending_up</span>
                    Tren Pengeluaran Gaji
                </h3>
                <div class="h-32 flex items-end gap-2 px-2">
                    <div class="flex-1 bg-primary-fixed-dim rounded-t h-[40%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-on-surface-variant font-bold">380M</div></div>
                    <div class="flex-1 bg-secondary-fixed rounded-t h-[55%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-on-surface-variant font-bold">395M</div></div>
                    <div class="flex-1 bg-primary-fixed-dim rounded-t h-[65%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-on-surface-variant font-bold">410M</div></div>
                    <div class="flex-1 bg-surface-variant rounded-t h-[60%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-on-surface-variant font-bold">405M</div></div>
                    <div class="flex-1 bg-secondary-fixed-dim rounded-t h-[75%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-on-surface-variant font-bold">415M</div></div>
                    <div class="flex-1 bg-secondary-container rounded-t h-[85%] relative group"><div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-label-caps text-secondary font-bold">425M</div></div>
                </div>
                <div class="flex justify-between mt-2 px-1">
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Nov</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Des</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Jan</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Feb</span>
                    <span class="text-[10px] text-on-surface-variant font-label-caps font-bold">Mar</span>
                    <span class="text-[10px] text-secondary font-bold font-label-caps">Apr</span>
                </div>
            </div>
            <!-- Leave Management -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 flex-1 shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant" data-icon="event_note">event_note</span>
                    Manajemen Cuti
                </h3>
                <div class="space-y-4 font-body-sm">
                    <div class="p-3 bg-error-container/20 rounded border border-error-container flex items-start gap-3">
                        <span class="material-symbols-outlined text-error mt-0.5" data-icon="pending_actions">pending_actions</span>
                        <div>
                            <p class="font-body-sm font-bold text-on-surface">{{ $pendingLeavesCount }} Pengajuan Menunggu</p>
                            <p class="font-body-sm text-on-surface-variant text-[11px] mt-0.5">Perlu approval dari HR Manager</p>
                        </div>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Sisa Cuti Terendah</p>
                        <ul class="space-y-2">
                            @foreach($lowestLeaveEmployees as $le)
                            <li class="flex justify-between text-body-sm items-center">
                                <span class="text-on-surface">{{ $le->name }}</span>
                                <span class="font-data-mono {{ $le->leave_balance <= 3 ? 'text-error font-bold' : 'text-on-surface-variant font-bold' }}">{{ $le->leave_balance }} Hari</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="openLeavesModal()" class="w-full text-secondary font-bold py-1 hover:underline text-left mt-2 font-label-caps text-label-caps">
                        Lihat Semua Pengajuan →
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Digital Documents Section (Bottom Full Width) -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 mt-2 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant" data-icon="description">description</span>
                Dokumen HR (Surat Kontrak)
            </h3>
            <div class="flex gap-2">
                <button onclick="openUploadModal()" aria-label="Upload" class="text-secondary hover:bg-secondary-fixed p-1.5 rounded transition-colors flex items-center gap-1 font-body-sm font-bold">
                    <span class="material-symbols-outlined" data-icon="upload_file">upload_file</span> Upload Dokumen
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($documents as $doc)
            <!-- Doc Card -->
            <div class="flex items-center p-4 border border-outline-variant rounded-lg hover:border-secondary transition-colors cursor-pointer group bg-surface-container-lowest">
                <div class="w-10 h-10 rounded bg-secondary-fixed flex items-center justify-center text-secondary mr-4">
                    <span class="material-symbols-outlined" data-icon="contract">contract</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-body-sm font-bold text-on-surface truncate group-hover:text-secondary transition-colors">{{ $doc->filename }}</p>
                    <p class="font-body-sm text-on-surface-variant text-[11px]">Diperbarui: {{ $doc->updated_at->format('d M Y') }} • {{ $doc->file_size }}</p>
                </div>
                <form action="{{ route('hr.documents.destroy', $doc->id) }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus dokumen ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-error hover:text-red-700 p-1">
                        <span class="material-symbols-outlined" data-icon="delete">delete</span>
                    </button>
                </form>
            </div>
            @endforeach

            @if($documents->isEmpty())
            <div class="col-span-3 p-8 text-center text-on-surface-variant">Tidak ada dokumen kontrak ditemukan.</div>
            @endif
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Input Karyawan Baru</h3>
            <button onclick="closeAddModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('hr.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-bold text-on-surface mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Sarah Johnson"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Jabatan</label>
                <input type="text" name="position" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Head of Piano Dept."/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Status Kontrak</label>
                <select name="contract_status" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    <option value="Permanent">Permanent</option>
                    <option value="Contract (6 Mo)">Contract (6 Mo)</option>
                    <option value="Contract (1 Yr)">Contract (1 Yr)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Gaji (Rp)</label>
                    <input type="number" name="salary" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: 15000000"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Sisa Jatah Cuti</label>
                    <input type="number" name="leave_balance" min="0" required value="12" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Edit Karyawan</h3>
            <button onclick="closeEditModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-on-surface mb-1">Nama Lengkap</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Jabatan</label>
                <input type="text" id="edit-position" name="position" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Status Kontrak</label>
                <select id="edit-contract-status" name="contract_status" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    <option value="Permanent">Permanent</option>
                    <option value="Contract (6 Mo)">Contract (6 Mo)</option>
                    <option value="Contract (1 Yr)">Contract (1 Yr)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Gaji (Rp)</label>
                    <input type="number" id="edit-salary" name="salary" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Sisa Jatah Cuti</label>
                    <input type="number" id="edit-leave-balance" name="leave_balance" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Ganti Foto Profil (Avatar)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Leaves Modal -->
<div id="leaves-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-lg shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Daftar Pengajuan Cuti</h3>
            <button onclick="closeLeavesModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="space-y-4 max-h-[300px] overflow-y-auto">
            @foreach($pendingLeaves as $pl)
            <div class="p-3 border border-outline-variant rounded flex justify-between items-center bg-surface-container-low font-body-sm">
                <div>
                    <p class="font-bold text-primary">{{ $pl->employee_name }}</p>
                    <p class="text-on-surface-variant text-[11px]">{{ $pl->leave_type }} • {{ $pl->duration }} Hari</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('hr.leaves.approve', $pl->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2.5 py-1 bg-secondary text-on-secondary rounded text-xs font-bold hover:bg-opacity-90">Setujui</button>
                    </form>
                    <form action="{{ route('hr.leaves.reject', $pl->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2.5 py-1 bg-error text-on-error rounded text-xs font-bold hover:bg-opacity-90">Tolak</button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($pendingLeaves->isEmpty())
            <p class="text-center text-on-surface-variant py-4">Tidak ada pengajuan cuti menunggu.</p>
            @endif
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="upload-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Upload Dokumen Kontrak</h3>
            <button onclick="closeUploadModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('hr.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-bold text-on-surface mb-1">File Dokumen (PDF, DOC, DOCX)</label>
                <input type="file" name="document_file" required accept=".pdf,.doc,.docx" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer"/>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeUploadModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-lg text-center font-body-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi</h3>
        <p id="confirm-message" class="text-on-surface-variant mb-6">Apakah Anda yakin?</p>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
            <button id="confirm-submit-btn" class="px-4 py-2 bg-secondary text-on-secondary rounded font-bold hover:bg-opacity-90 transition-colors">Proses</button>
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
