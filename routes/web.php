<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('courses', CourseController::class);
        Route::post('courses/{course}/assign-teacher', [CourseController::class, 'assignTeacher'])->name('courses.assign-teacher');
        Route::delete('courses/{course}/teachers/{teacher}', [CourseController::class, 'removeTeacher'])->name('courses.remove-teacher');
        Route::post('courses/{course}/enroll-student', [CourseController::class, 'enrollStudent'])->name('courses.enroll-student');
    });

    // Teacher routes
    Route::middleware(['role:teacher,admin'])->group(function () {
        Route::get('my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
        Route::get('grades/{course}', [GradeController::class, 'index'])->name('grades.index');
        Route::post('grades', [GradeController::class, 'store'])->name('grades.store');
    });

    // Student routes
    Route::middleware(['role:student,admin'])->group(function () {
        Route::get('my-grades', [GradeController::class, 'myGrades'])->name('my-grades');
    });
});
