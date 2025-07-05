<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index(Course $course)
    {
        // For teachers to view and manage grades for a specific course
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($course)) {
            abort(403, 'You are not assigned to this course.');
        }

        $enrollments = $course->enrollments()
            ->with(['student', 'grades'])
            ->where('is_active', true)
            ->get();

        return view('grades.index', compact('course', 'enrollments'));
    }

    public function create(Course $course, Enrollment $enrollment)
    {
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($course)) {
            abort(403, 'You are not assigned to this course.');
        }

        return view('grades.create', compact('course', 'enrollment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'evaluation_type' => 'required|string|max:255',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'evaluation_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        // Verify the score doesn't exceed max_score
        if ($request->score > $request->max_score) {
            return back()->withErrors(['score' => 'Score cannot exceed maximum score.']);
        }

        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($enrollment->course)) {
            abort(403, 'You are not assigned to this course.');
        }

        Grade::create($request->all());

        return redirect()->route('grades.index', $enrollment->course)
            ->with('success', 'Grade added successfully.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['enrollment.student', 'enrollment.course']);

        // Check permissions
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isTeacher()) {
            $teacher = $user->teacher;
            if (!$teacher->courses->contains($grade->enrollment->course)) {
                abort(403, 'You do not have permission to view this grade.');
            }
        } elseif ($user->isStudent()) {
            $student = $user->student;
            if ($grade->enrollment->student_id !== $student->id) {
                abort(403, 'You can only view your own grades.');
            }
        }

        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $grade->load(['enrollment.student', 'enrollment.course']);
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($grade->enrollment->course)) {
            abort(403, 'You are not assigned to this course.');
        }

        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'evaluation_type' => 'required|string|max:255',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'evaluation_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        // Verify the score doesn't exceed max_score
        if ($request->score > $request->max_score) {
            return back()->withErrors(['score' => 'Score cannot exceed maximum score.']);
        }

        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($grade->enrollment->course)) {
            abort(403, 'You are not assigned to this course.');
        }

        $grade->update($request->all());

        return redirect()->route('grades.index', $grade->enrollment->course)
            ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($grade->enrollment->course)) {
            abort(403, 'You are not assigned to this course.');
        }

        $course = $grade->enrollment->course;
        $grade->delete();

        return redirect()->route('grades.index', $course)
            ->with('success', 'Grade deleted successfully.');
    }

    public function myGrades()
    {
        // For students to view their own grades
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found. Please complete your profile.');
        }

        $enrollments = $student->enrollments()
            ->with(['course', 'grades' => function($query) {
                $query->orderBy('evaluation_date', 'desc');
            }])
            ->where('is_active', true)
            ->get();

        return view('grades.my-grades', compact('enrollments'));
    }

    public function courseGrades(Course $course)
    {
        // For students to view grades for a specific course
        $student = Auth::user()->student;

        $enrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->where('is_active', true)
            ->first();

        if (!$enrollment) {
            abort(404, 'You are not enrolled in this course.');
        }

        $grades = $enrollment->grades()
            ->orderBy('evaluation_date', 'desc')
            ->get();

        return view('grades.course-grades', compact('course', 'enrollment', 'grades'));
    }

    public function bulkGrade(Course $course)
    {
        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($course)) {
            abort(403, 'You are not assigned to this course.');
        }

        $enrollments = $course->enrollments()
            ->with('student')
            ->where('is_active', true)
            ->get();

        return view('grades.bulk-grade', compact('course', 'enrollments'));
    }

    public function storeBulkGrade(Request $request, Course $course)
    {
        $request->validate([
            'evaluation_type' => 'required|string|max:255',
            'max_score' => 'required|numeric|min:1',
            'evaluation_date' => 'required|date',
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.score' => 'required|numeric|min:0',
            'grades.*.comments' => 'nullable|string',
        ]);

        $teacher = Auth::user()->teacher;

        // Verify teacher is assigned to this course
        if (!$teacher->courses->contains($course)) {
            abort(403, 'You are not assigned to this course.');
        }

        $gradesData = [];
        foreach ($request->grades as $gradeData) {
            // Verify the score doesn't exceed max_score
            if ($gradeData['score'] > $request->max_score) {
                return back()->withErrors(['grades' => 'One or more scores exceed the maximum score.']);
            }

            $gradesData[] = [
                'enrollment_id' => $gradeData['enrollment_id'],
                'evaluation_type' => $request->evaluation_type,
                'score' => $gradeData['score'],
                'max_score' => $request->max_score,
                'evaluation_date' => $request->evaluation_date,
                'comments' => $gradeData['comments'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Grade::insert($gradesData);

        return redirect()->route('grades.index', $course)
            ->with('success', 'Bulk grades added successfully.');
    }
}
