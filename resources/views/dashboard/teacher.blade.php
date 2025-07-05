@extends('layouts.app')

@section('title', 'Dashboard - Teacher')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard</h2>
        <p class="text-muted">Welcome, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->count() }}</h4>
                        <p class="mb-0">My Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $total_students }}</h4>
                        <p class="mb-0">Total Students</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->where('is_active', true)->count() }}</h4>
                        <p class="mb-0">Active Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-play fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> My Courses</h5>
            </div>
            <div class="card-body">
                @if($courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Students</th>
                                <th>Credits</th>
                                <th>Schedule</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->course_code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $course->enrollments->count() }}</span>
                                </td>
                                <td>{{ $course->credits }}</td>
                                <td>{{ $course->schedule ?? 'Not set' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('grades.index', $course) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-chart-line"></i> Grades
                                        </a>
                                        <a href="{{ route('courses.show', $course) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No courses assigned yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
