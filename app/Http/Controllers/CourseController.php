<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['teachers', 'enrollments.student'])->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|unique:courses',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'level' => 'required|string|max:255',
            'schedule' => 'nullable|string',
            'classroom' => 'nullable|string|max:50',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load([
            'teachers',
            'enrollments.student',
            'enrollments.grades'
        ]);

        // Calculate statistics
        $totalStudents = $course->enrollments->where('status', 'active')->count();
        $averageGrade = $course->enrollments->flatMap->grades->avg('grade') ?? 0;
        $passRate = 0;

        if ($totalStudents > 0) {
            $passingStudents = $course->enrollments->filter(function ($enrollment) {
                return $enrollment->grades->avg('grade') >= 60;
            })->count();
            $passRate = ($passingStudents / $totalStudents) * 100;
        }

        $recentGrades = $course->enrollments->flatMap->grades
            ->sortByDesc('created_at')
            ->take(10);

        return view('courses.show', compact('course', 'totalStudents', 'averageGrade', 'passRate', 'recentGrades'));
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_code' => 'required|unique:courses,course_code,' . $course->id,
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'level' => 'required|string|max:255',
            'schedule' => 'nullable|string',
            'classroom' => 'nullable|string|max:50',
            'max_students' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive'
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function assignTeacher(Request $request, Course $course)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id'
        ]);

        $course->teachers()->attach($request->teacher_id);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Teacher assigned successfully.');
    }

    public function removeTeacher(Request $request, Course $course)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id'
        ]);

        $course->teachers()->detach($request->teacher_id);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Teacher removed successfully.');
    }

    public function enrollStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        // Check if student is already enrolled
        $existingEnrollment = Enrollment::where('course_id', $course->id)
            ->where('student_id', $request->student_id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Student is already enrolled in this course.');
        }

        // Check if course is full
        $currentEnrollments = $course->enrollments()->where('status', 'active')->count();
        if ($currentEnrollments >= $course->max_students) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Course is full. Cannot enroll more students.');
        }

        Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $course->id,
            'enrollment_date' => now(),
            'status' => 'active'
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Student enrolled successfully.');
    }

    public function myCourses()
    {
        $user = Auth::user();

        if ($user->role === 'teacher') {
            $teacher = $user->teacher;
            $courses = $teacher->courses()->with(['enrollments.student'])->get();
        } else {
            $courses = Course::with(['teachers', 'enrollments.student'])->get();
        }

        return view('courses.my-courses', compact('courses'));
    }
}
