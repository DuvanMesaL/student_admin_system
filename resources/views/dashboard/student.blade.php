@extends('layouts.app')

@section('title', 'Dashboard - Student')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-user-graduate"></i> Student Dashboard</h2>
        <p class="text-muted">Welcome, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $enrollments->count() }}</h4>
                        <p class="mb-0">Enrolled Courses</p>
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
                        <h4>{{ $enrollments->sum(function($e) { return $e->course->credits; }) }}</h4>
                        <p class="mb-0">Total Credits</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
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
                        <h4>{{ number_format($enrollments->avg('average_grade'), 1) }}</h4>
                        <p class="mb-0">Average Grade</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
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
                @if($enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Average Grade</th>
                                <th>Total Grades</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->course->course_code }}</td>
                                <td>{{ $enrollment->course->name }}</td>
                                <td>{{ $enrollment->course->credits }}</td>
                                <td>
                                    @if($enrollment->grades->count() > 0)
                                        <span class="badge bg-primary">{{ number_format($enrollment->average_grade, 1) }}</span>
                                    @else
                                        <span class="badge bg-secondary">No grades</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->grades->count() }}</td>
                                <td>
                                    <a href="{{ route('grades.course-grades', $enrollment->course) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-chart-line"></i> View Grades
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No courses enrolled yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
