<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Middleware\CheckRole;

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
    Route::middleware([CheckRole::class . ':admin'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('courses', CourseController::class);
        Route::post('courses/{course}/assign-teacher', [CourseController::class, 'assignTeacher'])->name('courses.assign-teacher');
        Route::post('courses/{course}/remove-teacher', [CourseController::class, 'removeTeacher'])->name('courses.remove-teacher');
        Route::post('courses/{course}/enroll-student', [CourseController::class, 'enrollStudent'])->name('courses.enroll-student');
        Route::delete('courses/{course}/teachers/{teacher}', [CourseController::class, 'removeTeacher'])->name('courses.remove-teacher');
        Route::post('courses/{course}/enroll-student', [CourseController::class, 'enrollStudent'])->name('courses.enroll-student');
    });

    // Teacher routes
    Route::middleware([CheckRole::class . ':teacher,admin'])->group(function () {
        Route::get('my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
        Route::get('grades/{course}', [GradeController::class, 'index'])->name('grades.index');
        Route::get('grades/{course}/enrollments/{enrollment}/create', [GradeController::class, 'create'])->name('grades.create');
        Route::post('grades', [GradeController::class, 'store'])->name('grades.store');
        Route::get('grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
        Route::get('grades/{course}/bulk', [GradeController::class, 'bulkGrade'])->name('grades.bulk-grade');
        Route::post('grades/{course}/bulk', [GradeController::class, 'storeBulkGrade'])->name('grades.store-bulk');
    });

    // Student routes
    Route::middleware([CheckRole::class . ':student,admin'])->group(function () {
        Route::get('my-grades', [GradeController::class, 'myGrades'])->name('my-grades');
        Route::get('courses/{course}/grades', [GradeController::class, 'courseGrades'])->name('grades.course-grades');
    });

    // Shared routes (all authenticated users)
    Route::get('courses/{course}/show', [CourseController::class, 'show'])->name('courses.show');
});
