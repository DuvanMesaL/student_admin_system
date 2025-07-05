@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chalkboard-teacher"></i> My Courses</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if($courses->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->count() }}</h4>
                        <p class="mb-0">Total Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->sum(function($course) { return $course->enrollments->count(); }) }}</h4>
                        <p class="mb-0">Total Students</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->sum('credits') }}</h4>
                        <p class="mb-0">Total Credits</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        @php
                            $totalGrades = $courses->sum(function($course) {
                                return $course->enrollments->sum(function($enrollment) {
                                    return $enrollment->grades->count();
                                });
                            });
                        @endphp
                        <h4>{{ $totalGrades }}</h4>
                        <p class="mb-0">Grades Recorded</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($courses as $course)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $course->name }}</h5>
                    <small class="text-muted">{{ $course->course_code }}</small>
                </div>
                <div>
                    @if($course->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($course->description)
                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                @endif

                <div class="row text-center mb-3">
                    <div class="col-4">
                        <h5 class="text-primary">{{ $course->enrollments->count() }}</h5>
                        <small class="text-muted">Students</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-info">{{ $course->credits }}</h5>
                        <small class="text-muted">Credits</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-success">{{ $course->enrollments->sum(function($e) { return $e->grades->count(); }) }}</h5>
                        <small class="text-muted">Grades</small>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Level:</strong>
                    <span class="badge bg-secondary">{{ $course->level }}</span>
                </div>

                @if($course->schedule)
                <div class="mb-3">
                    <strong>Schedule:</strong> {{ $course->schedule }}
                </div>
                @endif

                @if($course->enrollments->count() > 0)
                <div class="mb-3">
                    <h6>Recent Students:</h6>
                    @foreach($course->enrollments->take(3) as $enrollment)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small>{{ $enrollment->student->full_name }}</small>
                        @if($enrollment->grades->count() > 0)
                            <span class="badge bg-{{ $enrollment->average_grade >= 70 ? 'success' : 'warning' }} badge-sm">
                                {{ number_format($enrollment->average_grade, 1) }}
                            </span>
                        @else
                            <span class="badge bg-secondary badge-sm">No grades</span>
                        @endif
                    </div>
                    @endforeach
                    @if($course->enrollments->count() > 3)
                    <small class="text-muted">And {{ $course->enrollments->count() - 3 }} more...</small>
                    @endif
                </div>
                @endif

                @if($course->enrollments->count() > 0)
                @php
                    $classAverage = $course->enrollments->avg('average_grade');
                    $passingStudents = $course->enrollments->filter(function($enrollment) {
                        return $enrollment->average_grade >= 70;
                    })->count();
                    $totalWithGrades = $course->enrollments->filter(function($enrollment) {
                        return $enrollment->grades->count() > 0;
                    })->count();
                @endphp
                <div class="alert alert-info">
                    <small>
                        <strong>Class Performance:</strong><br>
                        Average: {{ $classAverage ? number_format($classAverage, 1) : 'N/A' }}<br>
                        Passing: {{ $passingStudents }}/{{ $totalWithGrades }} students
                    </small>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <div class="btn-group" role="group">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('grades.index', $course) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-chart-line"></i> Grades
                        </a>
                    </div>
                    @if($course->enrollments->count() > 0)
                    <a href="{{ route('grades.bulk-grade', $course) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle"></i> Bulk Grade
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Courses by Level</h5>
            </div>
            <div class="card-body">
                @php
                    $levelCounts = $courses->groupBy('level')->map->count();
                @endphp
                @foreach($levelCounts as $level => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $level }}</span>
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tasks"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @foreach($courses->where('enrollments_count', '>', 0)->take(3) as $course)
                    <a href="{{ route('grades.index', $course) }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-line"></i> Grade {{ $course->course_code }}
                    </a>
                    @endforeach

                    @if($courses->where('enrollments_count', '>', 0)->count() == 0)
                    <div class="text-center py-3">
                        <i class="fas fa-info-circle text-muted"></i>
                        <p class="text-muted mb-0">No courses with students to grade yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Courses Assigned</h4>
        <p class="text-muted">You haven't been assigned to any courses yet. Please contact your administrator.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="fas fa-home"></i> Go to Dashboard
        </a>
    </div>
</div>
@endif

@if($courses->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-calendar-alt"></i> Teaching Schedule Overview</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Schedule</th>
                        <th>Students</th>
                        <th>Next Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses->where('schedule', '!=', null) as $course)
                    <tr>
                        <td>
                            <strong>{{ $course->course_code }}</strong><br>
                            <small class="text-muted">{{ $course->name }}</small>
                        </td>
                        <td>{{ $course->schedule }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $course->enrollments->count() }}</span>
                        </td>
                        <td>
                            @if($course->enrollments->count() > 0)
                                @php
                                    $needsGrading = $course->enrollments->filter(function($enrollment) {
                                        return $enrollment->grades->where('created_at', '>=', now()->subWeek())->count() == 0;
                                    })->count();
                                @endphp
                                @if($needsGrading > 0)
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $needsGrading }} students need recent grades
                                    </small>
                                @else
                                    <small class="text-success">
                                        <i class="fas fa-check"></i>
                                        Up to date
                                    </small>
                                @endif
                            @else
                                <small class="text-muted">No students enrolled</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
