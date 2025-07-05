<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teacherUsers = User::where('role', 'teacher')->get();

        $teacherData = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'specialty' => 'Computer Science',
                'phone' => '555-1001',
                'document' => 'T12345001'
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'specialty' => 'Mathematics',
                'phone' => '555-1002',
                'document' => 'T12345002'
            ],
            [
                'first_name' => 'Carol',
                'last_name' => 'Williams',
                'specialty' => 'Physics',
                'phone' => '555-1003',
                'document' => 'T12345003'
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'specialty' => 'Chemistry',
                'phone' => '555-1004',
                'document' => 'T12345004'
            ],
            [
                'first_name' => 'Eva',
                'last_name' => 'Jones',
                'specialty' => 'Biology',
                'phone' => '555-1005',
                'document' => 'T12345005'
            ],
            [
                'first_name' => 'Frank',
                'last_name' => 'Garcia',
                'specialty' => 'English Literature',
                'phone' => '555-1006',
                'document' => 'T12345006'
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Martinez',
                'specialty' => 'History',
                'phone' => '555-1007',
                'document' => 'T12345007'
            ],
            [
                'first_name' => 'Henry',
                'last_name' => 'Lopez',
                'specialty' => 'Economics',
                'phone' => '555-1008',
                'document' => 'T12345008'
            ],
        ];

        foreach ($teacherUsers as $index => $user) {
            Teacher::create([
                'user_id' => $user->id,
                'teacher_code' => 'TCH' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'first_name' => $teacherData[$index]['first_name'],
                'last_name' => $teacherData[$index]['last_name'],
                'document_number' => $teacherData[$index]['document'],
                'phone' => $teacherData[$index]['phone'],
                'specialization' => $teacherData[$index]['specialty'],
                'status' => 'active',
            ]);
        }
    }
}
