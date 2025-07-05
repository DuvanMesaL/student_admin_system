<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;

class CourseTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teachers by specialty
        $csTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'sarah.johnson@school.edu');
        })->first();

        $mathTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'michael.chen@school.edu');
        })->first();

        $physicsTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'emily.rodriguez@school.edu');
        })->first();

        $chemTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'david.wilson@school.edu');
        })->first();

        $bioTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'lisa.thompson@school.edu');
        })->first();

        $engTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'james.anderson@school.edu');
        })->first();

        $histTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'maria.garcia@school.edu');
        })->first();

        $econTeacher = Teacher::whereHas('user', function($query) {
            $query->where('email', 'robert.taylor@school.edu');
        })->first();

        // Assign teachers to courses
        $assignments = [
            // CS Courses
            ['CS101', $csTeacher->id],
            ['CS201', $csTeacher->id],
            ['CS301', $csTeacher->id],

            // Math Courses
            ['MATH101', $mathTeacher->id],
            ['MATH201', $mathTeacher->id],
            ['MATH301', $mathTeacher->id],

            // Physics Courses
            ['PHYS101', $physicsTeacher->id],
            ['PHYS201', $physicsTeacher->id],

            // Chemistry Courses
            ['CHEM101', $chemTeacher->id],
            ['CHEM201', $chemTeacher->id],

            // Biology Courses
            ['BIO101', $bioTeacher->id],

            // English Courses
            ['ENG101', $engTeacher->id],

            // History Courses
            ['HIST101', $histTeacher->id],

            // Economics Courses
            ['ECON101', $econTeacher->id],
            ['ECON201', $econTeacher->id],
        ];

        foreach ($assignments as $assignment) {
            $course = Course::where('code', $assignment[0])->first();
            if ($course) {
                $course->teachers()->attach($assignment[1]);
            }
        }

        // Add some collaborative teaching (multiple teachers per course)
        $advancedCourses = Course::whereIn('code', ['CS301', 'PHYS201', 'CHEM201'])->get();
        foreach ($advancedCourses as $course) {
            // Add math teacher as support for advanced courses
            if (!$course->teachers->contains($mathTeacher->id)) {
                $course->teachers()->attach($mathTeacher->id);
            }
        }
    }
}
