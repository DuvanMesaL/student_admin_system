<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'teacher':
                return $this->teacherDashboard();
            case 'student':
                return $this->studentDashboard();
            default:
                return redirect()->route('login');
        }
    }

    private function adminDashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_courses' => Course::count(),
            'active_students' => Student::where('is_active', true)->count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        return view('dashboard.admin', compact('data'));
    }

    private function teacherDashboard()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('profile.complete');
        }

        $courses = $teacher->courses()->with('enrollments.student')->get();
        $total_students = $courses->sum(function($course) {
            return $course->enrollments->count();
        });

        return view('dashboard.teacher', compact('courses', 'total_students'));
    }

    private function studentDashboard()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('profile.complete');
        }

        $enrollments = $student->enrollments()
            ->with(['course', 'grades'])
            ->where('is_active', true)
            ->get();

        return view('dashboard.student', compact('enrollments'));
    }
}
