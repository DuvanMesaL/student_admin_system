<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@school.edu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Teacher Users
        $teachers = [
            ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah.johnson@school.edu', 'specialty' => 'Computer Science'],
            ['name' => 'Prof. Michael Chen', 'email' => 'michael.chen@school.edu', 'specialty' => 'Mathematics'],
            ['name' => 'Dr. Emily Rodriguez', 'email' => 'emily.rodriguez@school.edu', 'specialty' => 'Physics'],
            ['name' => 'Prof. David Wilson', 'email' => 'david.wilson@school.edu', 'specialty' => 'Chemistry'],
            ['name' => 'Dr. Lisa Thompson', 'email' => 'lisa.thompson@school.edu', 'specialty' => 'Biology'],
            ['name' => 'Prof. James Anderson', 'email' => 'james.anderson@school.edu', 'specialty' => 'English Literature'],
            ['name' => 'Dr. Maria Garcia', 'email' => 'maria.garcia@school.edu', 'specialty' => 'History'],
            ['name' => 'Prof. Robert Taylor', 'email' => 'robert.taylor@school.edu', 'specialty' => 'Economics'],
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('teacher123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ]);
        }

        // Create Student Users
        $students = [
            ['name' => 'John Smith', 'email' => 'john.smith@student.edu'],
            ['name' => 'Emma Johnson', 'email' => 'emma.johnson@student.edu'],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@student.edu'],
            ['name' => 'Sophia Davis', 'email' => 'sophia.davis@student.edu'],
            ['name' => 'William Miller', 'email' => 'william.miller@student.edu'],
            ['name' => 'Olivia Wilson', 'email' => 'olivia.wilson@student.edu'],
            ['name' => 'James Moore', 'email' => 'james.moore@student.edu'],
            ['name' => 'Isabella Taylor', 'email' => 'isabella.taylor@student.edu'],
            ['name' => 'Benjamin Anderson', 'email' => 'benjamin.anderson@student.edu'],
            ['name' => 'Charlotte Thomas', 'email' => 'charlotte.thomas@student.edu'],
            ['name' => 'Lucas Jackson', 'email' => 'lucas.jackson@student.edu'],
            ['name' => 'Amelia White', 'email' => 'amelia.white@student.edu'],
            ['name' => 'Henry Harris', 'email' => 'henry.harris@student.edu'],
            ['name' => 'Mia Martin', 'email' => 'mia.martin@student.edu'],
            ['name' => 'Alexander Thompson', 'email' => 'alexander.thompson@student.edu'],
            ['name' => 'Harper Garcia', 'email' => 'harper.garcia@student.edu'],
            ['name' => 'Daniel Martinez', 'email' => 'daniel.martinez@student.edu'],
            ['name' => 'Evelyn Robinson', 'email' => 'evelyn.robinson@student.edu'],
            ['name' => 'Matthew Clark', 'email' => 'matthew.clark@student.edu'],
            ['name' => 'Abigail Rodriguez', 'email' => 'abigail.rodriguez@student.edu'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('student123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }
    }
}
