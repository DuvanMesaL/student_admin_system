<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        // Define course levels for appropriate enrollment
        $freshmanCourses = Course::whereIn('code', ['CS101', 'MATH101', 'PHYS101', 'CHEM101', 'BIO101', 'ENG101', 'HIST101', 'ECON101'])->get();
        $sophomoreCourses = Course::whereIn('code', ['CS201', 'MATH201', 'ECON201'])->get();
        $juniorCourses = Course::whereIn('code', ['CS301', 'MATH301', 'PHYS201', 'CHEM201'])->get();

        foreach ($students as $student) {
            $enrollmentCount = 0;
            $maxEnrollments = rand(2, 5); // Each student enrolls in 2-5 courses

            // Enroll based on student level
            switch ($student->level) {
                case 'freshman':
                    $availableCourses = $freshmanCourses;
                    break;
                case 'sophomore':
                    $availableCourses = $freshmanCourses->merge($sophomoreCourses);
                    break;
                case 'junior':
                    $availableCourses = $freshmanCourses->merge($sophomoreCourses)->merge($juniorCourses);
                    break;
                case 'senior':
                    $availableCourses = $courses; // Seniors can take any course
                    break;
                default:
                    $availableCourses = $freshmanCourses;
            }

            $shuffledCourses = $availableCourses->shuffle();

            foreach ($shuffledCourses as $course) {
                if ($enrollmentCount >= $maxEnrollments) {
                    break;
                }

                // Check if already enrolled
                $existingEnrollment = Enrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$existingEnrollment) {
                    // Check course capacity
                    $currentEnrollments = Enrollment::where('course_id', $course->id)
                        ->where('status', 'active')
                        ->count();

                    if ($currentEnrollments < $course->max_students) {
                        $enrollmentDate = now()->subDays(rand(1, 90)); // Enrolled within last 90 days
                        $status = rand(1, 10) > 1 ? 'active' : 'inactive'; // 90% active, 10% inactive

                        Enrollment::create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'enrollment_date' => $enrollmentDate,
                            'status' => $status,
                        ]);

                        $enrollmentCount++;
                    }
                }
            }
        }
    }
}
