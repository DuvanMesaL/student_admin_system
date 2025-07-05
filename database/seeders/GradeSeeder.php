<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = Enrollment::where('status', 'active')->get();

        $gradeTypes = ['Quiz', 'Assignment', 'Midterm', 'Final', 'Project', 'Participation'];

        foreach ($enrollments as $enrollment) {
            $numGrades = rand(3, 8); // Each student gets 3-8 grades per course

            // Determine student performance level (affects grade distribution)
            $performanceLevel = $this->getStudentPerformanceLevel();

            for ($i = 0; $i < $numGrades; $i++) {
                $gradeType = $gradeTypes[array_rand($gradeTypes)];
                $grade = $this->generateGradeByPerformance($performanceLevel, $gradeType);

                Grade::create([
                    'enrollment_id' => $enrollment->id,
                    'grade_type' => $gradeType,
                    'grade' => $grade,
                    'max_grade' => 100,
                    'comments' => $this->generateComments($grade, $gradeType),
                    'graded_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }

    private function getStudentPerformanceLevel()
    {
        $rand = rand(1, 100);

        if ($rand <= 15) return 'excellent';    // 15% excellent students
        if ($rand <= 40) return 'good';         // 25% good students
        if ($rand <= 75) return 'average';      // 35% average students
        return 'struggling';                    // 25% struggling students
    }

    private function generateGradeByPerformance($performanceLevel, $gradeType)
    {
        switch ($performanceLevel) {
            case 'excellent':
                return rand(90, 100);
            case 'good':
                return rand(80, 95);
            case 'average':
                return rand(65, 85);
            case 'struggling':
                return rand(40, 70);
            default:
                return rand(60, 80);
        }
    }

    private function generateComments($grade, $gradeType)
    {
        if ($grade >= 90) {
            $comments = [
                'Excellent work! Shows deep understanding of the material.',
                'Outstanding performance. Keep up the great work!',
                'Exceptional quality. Demonstrates mastery of concepts.',
                'Impressive work. Shows creativity and critical thinking.',
                'Excellent job! Your effort really shows.'
            ];
        } elseif ($grade >= 80) {
            $comments = [
                'Good work! Shows solid understanding.',
                'Well done. Minor areas for improvement noted.',
                'Good effort. Keep working on the details.',
                'Nice job! Shows good grasp of the material.',
                'Good work overall. Some room for improvement.'
            ];
        } elseif ($grade >= 70) {
            $comments = [
                'Satisfactory work. Meets basic requirements.',
                'Average performance. Consider reviewing key concepts.',
                'Acceptable work. Could benefit from more practice.',
                'Meets expectations. Room for improvement in some areas.',
                'Fair work. Focus on understanding core concepts.'
            ];
        } elseif ($grade >= 60) {
            $comments = [
                'Below expectations. Please review the material.',
                'Needs improvement. Consider seeking additional help.',
                'Struggling with key concepts. Extra study recommended.',
                'Below average. Please see me during office hours.',
                'Needs work. Review notes and practice problems.'
            ];
        } else {
            $comments = [
                'Significant improvement needed. Please schedule a meeting.',
                'Well below expectations. Immediate attention required.',
                'Major gaps in understanding. Tutoring recommended.',
                'Unsatisfactory. Please see me as soon as possible.',
                'Needs substantial improvement. Consider additional resources.'
            ];
        }

        return $comments[array_rand($comments)];
    }
}
