<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        User::create([
            'name' => 'Administrator NexaLearn',
            'email' => 'admin@nexalearn.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'bio' => 'Administrator sistem pembelajaran digital NexaLearn.',
            'avatar' => null, // Will use initials CSS avatar
        ]);

        $instructor1 = User::create([
            'name' => 'Dr. Rian Hermawan',
            'email' => 'rian@nexalearn.com',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'bio' => 'Dosen Senior Jurusan Kimia & Sains Terapan. Lulusan Kimia ITB dengan riset fokus Kimia Organik.',
            'avatar' => null,
        ]);

        $instructor2 = User::create([
            'name' => 'Prof. Sarah Handayani',
            'email' => 'sarah@nexalearn.com',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'bio' => 'Kepala Pusat Bahasa NexaLearn. Pengajar Bahasa Inggris Akademik dan Persiapan TOEFL.',
            'avatar' => null,
        ]);

        $student = User::create([
            'name' => 'Kevin Setiawan',
            'email' => 'student@nexalearn.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'bio' => 'Mahasiswa NexaLearn program studi Kimia tingkat akhir.',
            'avatar' => null,
        ]);

        // 2. Create Categories
        $sains = Category::create([
            'name' => 'Kimia & Sains (MIPA)',
            'slug' => 'kimia-sains-mipa',
            'description' => 'Materi pelajaran Kimia, Fisika, Biologi, dan Matematika tingkat SMA.',
            'icon' => 'flask',
        ]);

        $bahasa = Category::create([
            'name' => 'Bahasa & Literasi',
            'slug' => 'bahasa-literasi',
            'description' => 'Pembelajaran Bahasa Indonesia, Bahasa Inggris, dan kemampuan menulis kreatif.',
            'icon' => 'translate',
        ]);

        $akademik = Category::create([
            'name' => 'IPS (Ilmu Pengetahuan Sosial)',
            'slug' => 'ips-ilmu-sosial',
            'description' => 'Materi pelajaran Sejarah, Geografi, Sosiologi, dan Ekonomi tingkat SMA.',
            'icon' => 'globe',
        ]);

        // 3. (Courses seeding removed so database starts with 0 courses)
    }
}
