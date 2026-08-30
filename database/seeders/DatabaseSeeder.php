<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Inventory;
use App\Models\Employee;
use App\Models\HrLeave;
use App\Models\HrDocument;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with realistic operational data.
     */
    public function run(): void
    {
        // 1. Seed Master User
        User::updateOrCreate(
            ['email' => 'mastervi@proatsmusic.com'],
            [
                'name' => 'mastervi',
                'password' => Hash::make('pisangkeju'),
            ]
        );

        // 2. Seed Vendors
        $vendors = [
            [
                'name' => 'Yamaha Music Indonesia',
                'category' => 'Alat Musik',
                'contact' => 'sales@yamaha.co.id',
                'address' => 'Jl. Jend. Gatot Subroto Kav. 4, Jakarta',
                'status' => 'Aktif',
                'procurement_cost' => 150000000.00
            ],
            [
                'name' => 'Gibson Brands',
                'category' => 'Alat Musik',
                'contact' => 'info@gibson.com',
                'address' => 'Nashville, Tennessee, USA',
                'status' => 'Aktif',
                'procurement_cost' => 95000000.00
            ],
            [
                'name' => 'Roland Corporation',
                'category' => 'Alat Musik',
                'contact' => 'support@roland.com',
                'address' => 'Hamamatsu, Shizuoka, Japan',
                'status' => 'Aktif',
                'procurement_cost' => 64000000.00
            ],
            [
                'name' => 'Swee Lee',
                'category' => 'Aksesori',
                'contact' => 'contact@sweelee.co.id',
                'address' => 'Jl. Metro Pondok Indah, Jakarta',
                'status' => 'Aktif',
                'procurement_cost' => 25000000.00
            ],
            [
                'name' => 'Music Care Service',
                'category' => 'Service',
                'contact' => 'service@musiccare.com',
                'address' => 'Jl. Ir. H. Juanda No. 120, Bandung',
                'status' => 'Aktif',
                'procurement_cost' => 8000000.00
            ]
        ];

        foreach ($vendors as $v) {
            Vendor::updateOrCreate(['name' => $v['name']], $v);
        }

        // 3. Seed Inventories
        $inventories = [
            [
                'item_id' => 'INV-204',
                'name' => 'Yamaha U1 Upright Piano',
                'category' => 'Pianos',
                'stock' => 3,
                'price' => 95000000.00,
                'image_path' => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Yamaha Music Indonesia'
            ],
            [
                'item_id' => 'INV-881',
                'name' => 'Gibson Les Paul Standard',
                'category' => 'Guitars',
                'stock' => 4,
                'price' => 38000000.00,
                'image_path' => 'https://images.unsplash.com/photo-1550985616-10810253b84d?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Gibson Brands'
            ],
            [
                'item_id' => 'INV-612',
                'name' => 'Fender Stratocaster Professional',
                'category' => 'Guitars',
                'stock' => 6,
                'price' => 28000000.00,
                'image_path' => 'https://images.unsplash.com/photo-1564186763535-ebb21ef5278f?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Swee Lee'
            ],
            [
                'item_id' => 'INV-109',
                'name' => 'Roland TD-17KVX V-Drums',
                'category' => 'Drums',
                'stock' => 2,
                'price' => 24000000.00,
                'image_path' => 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Roland Corporation'
            ],
            [
                'item_id' => 'INV-733',
                'name' => 'Boss DS-1 Distortion Pedal',
                'category' => 'Accessories',
                'stock' => 15,
                'price' => 1200000.00,
                'image_path' => 'https://images.unsplash.com/photo-1598150490543-de4e8c3359d9?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Swee Lee'
            ],
            [
                'item_id' => 'INV-042',
                'name' => 'D\'Addario EXL110 Guitar Strings',
                'category' => 'Accessories',
                'stock' => 45,
                'price' => 95000.00,
                'image_path' => 'https://images.unsplash.com/photo-1608155686393-8fdd966d784d?auto=format&fit=crop&w=300&q=80',
                'vendor' => 'Swee Lee'
            ]
        ];

        foreach ($inventories as $inv) {
            Inventory::updateOrCreate(['item_id' => $inv['item_id']], $inv);
        }

        // 4. Seed Employees
        $employees = [
            [
                'employee_id' => 'EMP-718',
                'name' => 'Sarah Johnson',
                'position' => 'Head of Piano Dept.',
                'contract_status' => 'Permanent',
                'salary' => 15000000.00,
                'leave_balance' => 12,
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80'
            ],
            [
                'employee_id' => 'EMP-412',
                'name' => 'Michael Green',
                'position' => 'Senior Guitar Instructor',
                'contract_status' => 'Permanent',
                'salary' => 12000000.00,
                'leave_balance' => 10,
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80'
            ],
            [
                'employee_id' => 'EMP-251',
                'name' => 'David Miller',
                'position' => 'Logistics Manager',
                'contract_status' => 'Permanent',
                'salary' => 9500000.00,
                'leave_balance' => 8,
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80'
            ],
            [
                'employee_id' => 'EMP-388',
                'name' => 'Amanda Rose',
                'position' => 'Frontdesk & Relations',
                'contract_status' => 'Contract (1 Yr)',
                'salary' => 6500000.00,
                'leave_balance' => 12,
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=150&h=150&q=80'
            ],
            [
                'employee_id' => 'EMP-922',
                'name' => 'Robert Chen',
                'position' => 'Drum Instructor',
                'contract_status' => 'Contract (6 Mo)',
                'salary' => 8000000.00,
                'leave_balance' => 14,
                'avatar' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=150&h=150&q=80'
            ]
        ];

        foreach ($employees as $emp) {
            Employee::updateOrCreate(['employee_id' => $emp['employee_id']], $emp);
        }

        // 5. Seed HR Leaves
        $leaves = [
            [
                'employee_name' => 'Sarah Johnson',
                'leave_type' => 'Cuti Tahunan',
                'duration' => 3,
                'status' => 'Menunggu'
            ],
            [
                'employee_name' => 'Michael Green',
                'leave_type' => 'Cuti Sakit',
                'duration' => 2,
                'status' => 'Disetujui'
            ],
            [
                'employee_name' => 'Amanda Rose',
                'leave_type' => 'Cuti Melahirkan',
                'duration' => 5,
                'status' => 'Menunggu'
            ]
        ];

        foreach ($leaves as $l) {
            HrLeave::updateOrCreate(
                ['employee_name' => $l['employee_name'], 'leave_type' => $l['leave_type']],
                $l
            );
        }

        // 6. Seed HR Documents
        $documents = [
            [
                'filename' => 'Surat_Kontrak_Sarah_Johnson.pdf',
                'file_path' => '/uploads/documents/Surat_Kontrak_Sarah_Johnson.pdf',
                'file_size' => '1.2 MB'
            ],
            [
                'filename' => 'MoU_Kerjasama_Yamaha_Music.pdf',
                'file_path' => '/uploads/documents/MoU_Kerjasama_Yamaha_Music.pdf',
                'file_size' => '845 KB'
            ],
            [
                'filename' => 'MoU_Swee_Lee_Procurement_2026.pdf',
                'file_path' => '/uploads/documents/MoU_Swee_Lee_Procurement_2026.pdf',
                'file_size' => '2.1 MB'
            ]
        ];

        foreach ($documents as $doc) {
            HrDocument::updateOrCreate(['filename' => $doc['filename']], $doc);
        }

        // 7. Seed Transactions spanning 6 months (Today is Aug 2026)
        $transactions = [
            // March 2026 (Income: 45M, Expense: 30M)
            [
                'transaction_id' => 'TRX-10001',
                'type' => 'income',
                'description' => 'SPP Bulanan Kursus - Maret 2026',
                'category' => 'Kursus',
                'amount' => 45000000.00,
                'date' => '2026-03-10'
            ],
            [
                'transaction_id' => 'TRX-10002',
                'type' => 'expense',
                'description' => 'Gaji Karyawan - Maret 2026',
                'category' => 'Operasional',
                'amount' => 30000000.00,
                'date' => '2026-03-28'
            ],

            // April 2026 (Income: 52M, Expense: 38M)
            [
                'transaction_id' => 'TRX-20001',
                'type' => 'income',
                'description' => 'Penjualan Tiket Piano Recital',
                'category' => 'Konser',
                'amount' => 52000000.00,
                'date' => '2026-04-12'
            ],
            [
                'transaction_id' => 'TRX-20002',
                'type' => 'expense',
                'description' => 'Sewa Gedung Konser & Sound System',
                'category' => 'Logistik',
                'amount' => 38000000.00,
                'date' => '2026-04-10'
            ],

            // May 2026 (Income: 60M, Expense: 42M)
            [
                'transaction_id' => 'TRX-30001',
                'type' => 'income',
                'description' => 'SPP Bulanan Kursus - Mei 2026',
                'category' => 'Kursus',
                'amount' => 60000000.00,
                'date' => '2026-05-10'
            ],
            [
                'transaction_id' => 'TRX-30002',
                'type' => 'expense',
                'description' => 'Pengadaan Aksesori Fender & Boss',
                'category' => 'Inventaris',
                'amount' => 42000000.00,
                'date' => '2026-05-18'
            ],

            // June 2026 (Income: 48M, Expense: 35M)
            [
                'transaction_id' => 'TRX-40001',
                'type' => 'income',
                'description' => 'Penjualan 1 unit Fender Stratocaster',
                'category' => 'Penjualan',
                'amount' => 48000000.00,
                'date' => '2026-06-05'
            ],
            [
                'transaction_id' => 'TRX-40002',
                'type' => 'expense',
                'description' => 'Service & Tuning Piano Yamaha',
                'category' => 'Maintenance',
                'amount' => 35000000.00,
                'date' => '2026-06-20'
            ],

            // July 2026 (Income: 55M, Expense: 40M)
            [
                'transaction_id' => 'TRX-50001',
                'type' => 'income',
                'description' => 'Sponsorship Konser Musik Proats',
                'category' => 'Sponsor',
                'amount' => 55000000.00,
                'date' => '2026-07-15'
            ],
            [
                'transaction_id' => 'TRX-50002',
                'type' => 'expense',
                'description' => 'Promosi & Brosur Pendaftaran Siswa Baru',
                'category' => 'Marketing',
                'amount' => 40000000.00,
                'date' => '2026-07-22'
            ],

            // August 2026 (Income: 64M, Expense: 51M)
            [
                'transaction_id' => 'TRX-60001',
                'type' => 'income',
                'description' => 'SPP Bulanan Kursus - Agustus 2026',
                'category' => 'Kursus',
                'amount' => 64000000.00,
                'date' => '2026-08-10'
            ],
            [
                'transaction_id' => 'TRX-60002',
                'type' => 'expense',
                'description' => 'Gaji Karyawan & Bonus Staf - Agustus 2026',
                'category' => 'Operasional',
                'amount' => 51000000.00,
                'date' => '2026-08-28'
            ]
        ];

        foreach ($transactions as $trx) {
            Transaction::updateOrCreate(['transaction_id' => $trx['transaction_id']], $trx);
        }
    }
}
