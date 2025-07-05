<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Introduction to Computer Science',
                'code' => 'CS101',
                'description' => 'Fundamental concepts of computer science including programming basics, algorithms, and data structures.',
                'credits' => 3,
                'schedule' => 'Mon/Wed/Fri 9:00-10:00 AM',
                'classroom' => 'CS-101',
                'max_students' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Data Structures and Algorithms',
                'code' => 'CS201',
                'description' => 'Advanced study of data structures, algorithm design, and complexity analysis.',
                'credits' => 4,
                'schedule' => 'Tue/Thu 10:30-12:00 PM',
                'classroom' => 'CS-102',
                'max_students' => 25,
                'status' => 'active'
            ],
            [
                'name' => 'Database Systems',
                'code' => 'CS301',
                'description' => 'Design and implementation of database systems, SQL, and database management.',
                'credits' => 3,
                'schedule' => 'Mon/Wed 2:00-3:30 PM',
                'classroom' => 'CS-103',
                'max_students' => 20,
                'status' => 'active'
            ],
            [
                'name' => 'Calculus I',
                'code' => 'MATH101',
                'description' => 'Limits, derivatives, and applications of differential calculus.',
                'credits' => 4,
                'schedule' => 'Mon/Wed/Fri 8:00-9:00 AM',
                'classroom' => 'MATH-201',
                'max_students' => 35,
                'status' => 'active'
            ],
            [
                'name' => 'Linear Algebra',
                'code' => 'MATH201',
                'description' => 'Vector spaces, matrices, eigenvalues, and linear transformations.',
                'credits' => 3,
                'schedule' => 'Tue/Thu 1:00-2:30 PM',
                'classroom' => 'MATH-202',
                'max_students' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Statistics',
                'code' => 'MATH301',
                'description' => 'Probability theory, statistical inference, and data analysis.',
                'credits' => 3,
                'schedule' => 'Mon/Wed 11:00-12:30 PM',
                'classroom' => 'MATH-203',
                'max_students' => 28,
                'status' => 'active'
            ],
            [
                'name' => 'General Physics I',
                'code' => 'PHYS101',
                'description' => 'Mechanics, thermodynamics, and wave motion with laboratory component.',
                'credits' => 4,
                'schedule' => 'Tue/Thu 9:00-10:30 AM, Lab: Fri 2:00-5:00 PM',
                'classroom' => 'PHYS-101',
                'max_students' => 24,
                'status' => 'active'
            ],
            [
                'name' => 'Electromagnetism',
                'code' => 'PHYS201',
                'description' => 'Electric and magnetic fields, electromagnetic waves, and applications.',
                'credits' => 4,
                'schedule' => 'Mon/Wed 3:00-4:30 PM, Lab: Thu 2:00-5:00 PM',
                'classroom' => 'PHYS-102',
                'max_students' => 20,
                'status' => 'active'
            ],
            [
                'name' => 'General Chemistry',
                'code' => 'CHEM101',
                'description' => 'Atomic structure, chemical bonding, and basic chemical reactions.',
                'credits' => 4,
                'schedule' => 'Mon/Wed/Fri 10:00-11:00 AM, Lab: Tue 1:00-4:00 PM',
                'classroom' => 'CHEM-101',
                'max_students' => 22,
                'status' => 'active'
            ],
            [
                'name' => 'Organic Chemistry',
                'code' => 'CHEM201',
                'description' => 'Structure, properties, and reactions of organic compounds.',
                'credits' => 4,
                'schedule' => 'Tue/Thu 11:00-12:30 PM, Lab: Wed 2:00-5:00 PM',
                'classroom' => 'CHEM-102',
                'max_students' => 18,
                'status' => 'active'
            ],
            [
                'name' => 'Introduction to Biology',
                'code' => 'BIO101',
                'description' => 'Cell biology, genetics, evolution, and ecology fundamentals.',
                'credits' => 4,
                'schedule' => 'Mon/Wed/Fri 1:00-2:00 PM, Lab: Thu 9:00-12:00 PM',
                'classroom' => 'BIO-101',
                'max_students' => 26,
                'status' => 'active'
            ],
            [
                'name' => 'English Composition',
                'code' => 'ENG101',
                'description' => 'Academic writing, critical thinking, and communication skills.',
                'credits' => 3,
                'schedule' => 'Tue/Thu 8:00-9:30 AM',
                'classroom' => 'ENG-101',
                'max_students' => 25,
                'status' => 'active'
            ],
            [
                'name' => 'World History',
                'code' => 'HIST101',
                'description' => 'Survey of world civilizations from ancient times to present.',
                'credits' => 3,
                'schedule' => 'Mon/Wed 4:00-5:30 PM',
                'classroom' => 'HIST-101',
                'max_students' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Microeconomics',
                'code' => 'ECON101',
                'description' => 'Individual economic behavior, market structures, and resource allocation.',
                'credits' => 3,
                'schedule' => 'Tue/Thu 2:30-4:00 PM',
                'classroom' => 'ECON-101',
                'max_students' => 32,
                'status' => 'active'
            ],
            [
                'name' => 'Macroeconomics',
                'code' => 'ECON201',
                'description' => 'National economic systems, monetary policy, and international trade.',
                'credits' => 3,
                'schedule' => 'Mon/Wed 12:30-2:00 PM',
                'classroom' => 'ECON-102',
                'max_students' => 28,
                'status' => 'active'
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
