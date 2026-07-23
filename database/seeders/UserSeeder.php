<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * CATATAN SKEMA
     * ---------------------------------------------------------------------
     * Tabel `users` TIDAK punya kolom mentor_id. Relasi mentor <-> mahasiswa
     * disimpan di tabel `bimbingan_requests` (student_id, mentor_id, status).
     * Status 'approved' berarti mahasiswa itu resmi dibimbing mentor terkait
     * — lihat migration create_bimbingan_requests_table & User::students()/mentor().
     * ---------------------------------------------------------------------
     */
    public function run(): void
    {
        // ===== Mentor =====
        $mentorData = [
            ['name' => 'Dr. Andi Wijaya', 'email' => 'andi.wijaya@internlog.test'],
            ['name' => 'Siti Rahmawati, M.Kom', 'email' => 'siti.rahmawati@internlog.test'],
        ];

        $mentors = collect($mentorData)->map(function (array $data) {
            return User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'mentor',
                    'email_verified_at' => now(),
                ]
            );
        });

        // ===== Mahasiswa (dibagi rata ke tiap mentor di atas) =====
        $studentData = [
            ['name' => 'Raka Pradipta', 'university' => 'Universitas Sebelas Maret', 'major' => 'Informatika', 'degree_level' => 'S1'],
            ['name' => 'Dinda Amelia', 'university' => 'Universitas Gadjah Mada', 'major' => 'Sistem Informasi', 'degree_level' => 'S1'],
            ['name' => 'Fajar Setiawan', 'university' => 'Politeknik Negeri Semarang', 'major' => 'Teknik Informatika', 'degree_level' => 'D4'],
            ['name' => 'Nabila Putri', 'university' => 'Universitas Diponegoro', 'major' => 'Ilmu Komputer', 'degree_level' => 'S1'],
            ['name' => 'Yoga Prasetyo', 'university' => 'Politeknik Negeri Malang', 'major' => 'Manajemen Informatika', 'degree_level' => 'D3'],
            ['name' => 'Salsabila Ramadhani', 'university' => 'Universitas Sebelas Maret', 'major' => 'Informatika', 'degree_level' => 'S1'],
        ];

        foreach ($studentData as $index => $data) {
            $mentor = $mentors[$index % $mentors->count()];

            $student = User::firstOrCreate(
                ['email' => Str::slug($data['name'], '.') . '@internlog.test'],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'mahasiswa',
                    'university' => $data['university'],
                    'major' => $data['major'],
                    'degree_level' => $data['degree_level'],
                    'email_verified_at' => now(),
                ]
            );

            $this->assignMentor($student->id, $mentor->id);
        }

        // ===== Akun cepat untuk login manual saat development =====
        $demoMentor = User::firstOrCreate(
            ['email' => 'mentor@internlog.test'],
            [
                'name' => 'Mentor Demo',
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'email_verified_at' => now(),
            ]
        );

        $demoStudent = User::firstOrCreate(
            ['email' => 'mahasiswa@internlog.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'university' => 'Universitas Sebelas Maret',
                'major' => 'Informatika',
                'degree_level' => 'S1',
                'email_verified_at' => now(),
            ]
        );

        $this->assignMentor($demoStudent->id, $demoMentor->id);

        // ===== Satu mahasiswa TANPA mentor, buat testing alur "Cari Mentor" =====
        User::firstOrCreate(
            ['email' => 'belumbimbingan@internlog.test'],
            [
                'name' => 'Mahasiswa Belum Bimbingan',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'university' => 'Universitas Sebelas Maret',
                'major' => 'Teknik Elektro',
                'degree_level' => 'S1',
                'email_verified_at' => now(),
            ]
        );
    }

    /**
     * Simpan pasangan mentor-mahasiswa langsung berstatus 'approved' ke
     * bimbingan_requests. Dicek dulu supaya aman dijalankan berkali-kali
     * (student_id + mentor_id unique bersama).
     */
    private function assignMentor(int $studentId, int $mentorId): void
    {
        $alreadyAssigned = DB::table('bimbingan_requests')
            ->where('student_id', $studentId)
            ->where('mentor_id', $mentorId)
            ->exists();

        if (! $alreadyAssigned) {
            DB::table('bimbingan_requests')->insert([
                'student_id' => $studentId,
                'mentor_id' => $mentorId,
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
