<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin account — login with NIP + password 'wildani123'
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@wildani.sch.id',
            'password' => bcrypt('wildani123'),
            'role' => 'admin',
            'nip' => '198501012010011001',
        ]);

        // Payment types with specific icons and amounts
        $paymentTypes = [
            [
                'name' => 'SPP Bulanan',
                'slug' => 'spp-bulanan',
                'description' => 'Pembayaran Sumbangan Pembinaan Pendidikan bulanan',
                'icon' => 'calendar',
                'amount' => 350000,
            ],
            [
                'name' => 'Uang Gedung',
                'slug' => 'uang-gedung',
                'description' => 'Pembayaran uang gedung dan fasilitas sekolah',
                'icon' => 'building',
                'amount' => 2500000,
            ],
            [
                'name' => 'Pembelian Seragam',
                'slug' => 'pembelian-seragam',
                'description' => 'Pembelian seragam sekolah lengkap',
                'icon' => 'shirt',
                'amount' => 450000,
            ],
            [
                'name' => 'Uang Kegiatan',
                'slug' => 'uang-kegiatan',
                'description' => 'Biaya kegiatan, buku & alat tulis, wisuda, dan acara sekolah',
                'icon' => 'sparkles',
                'amount' => 875000,
            ],
        ];

        $createdTypes = [];
        foreach ($paymentTypes as $type) {
            $createdTypes[] = PaymentType::create($type);
        }

        // Sample students — login with NISN + password 'siswa123'
        $students = [
            ['nisn' => '0051234001', 'nama' => 'Aisyah Putri Ramadhani', 'kelas' => 'TK A', 'parent_phone' => '081234567890'],
            ['nisn' => '0051234002', 'nama' => 'Muhammad Rizky Pratama', 'kelas' => 'TK B', 'parent_phone' => '082345678901'],
            ['nisn' => '0051234003', 'nama' => 'Zahra Amelia Sari', 'kelas' => 'PAUD', 'parent_phone' => '083456789012'],
            ['nisn' => '0051234004', 'nama' => 'Ahmad Fauzan Hakim', 'kelas' => 'TK A', 'parent_phone' => '084567890123'],
            ['nisn' => '0051234005', 'nama' => 'Naura Khayla Azzahra', 'kelas' => 'TK B', 'parent_phone' => '085678901234'],
        ];

        foreach ($students as $studentData) {
            $student = Student::create($studentData);

            // Create student login account — same password for all students
            User::create([
                'name' => $student->nama,
                'email' => strtolower(explode(' ', trim($student->nama))[0]).'@wildani.sch.id',
                'password' => bcrypt('siswa123'),
                'role' => 'siswa',
                'nisn' => $student->nisn,
                'student_id' => $student->id,
            ]);

            // Create pending bills for each student (SPP + a random type)
            Bill::create([
                'student_id' => $student->id,
                'payment_type_id' => $createdTypes[0]->id, // SPP Bulanan
                'amount' => $createdTypes[0]->amount,
                'status' => 'pending',
                'due_date' => now()->endOfMonth(),
            ]);

            // Add a second random bill
            $randomType = $createdTypes[array_rand(array_slice($createdTypes, 1)) + 1];
            Bill::create([
                'student_id' => $student->id,
                'payment_type_id' => $randomType->id,
                'amount' => $randomType->amount,
                'status' => 'pending',
                'due_date' => now()->addMonths(1)->endOfMonth(),
            ]);
        }
    }
}
