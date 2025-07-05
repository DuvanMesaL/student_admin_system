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
        $courses = Course::with('teachers')->paginate(10);
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
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'level' => 'required|string|max:255',
            'schedule' => 'nullable|string',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['teachers', 'enrollments.student']);
        return view('courses.show', compact('course'));
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
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'level' => 'required|string|max:255',
            'schedule' => 'nullable|string',
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
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $course->teachers()->attach($request->teacher_id);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Teacher assigned to course successfully.');
    }

    public function myCourses()
    {
        $teacher =  Auth::user()->teacher;
        $courses = $teacher->courses()->with('enrollments.student')->get();

        return view('courses.my-courses', compact('courses'));
    }

    public function removeTeacher(Course $course, Teacher $teacher)
    {
        $course->teachers()->detach($teacher->id);
        return redirect()->route('courses.show', $course)->with('success', 'Teacher removed from course successfully.');
    }

    public function enrollStudent(Request $request, Course $course)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'enrollment_date' => 'required|date',
        ]);

        Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $course->id,
            'enrollment_date' => $request->enrollment_date,
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Student enrolled successfully.');
    }
}
