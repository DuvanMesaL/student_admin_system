@extends('layouts.app')

@section('title', 'Student Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-graduate"></i> Student Details</h2>
    <div>
        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Student Code:</strong></td>
                        <td>{{ $student->student_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Full Name:</strong></td>
                        <td>{{ $student->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Document:</strong></td>
                        <td>{{ $student->document_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Birth Date:</strong></td>
                        <td>{{ $student->birth_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Age:</strong></td>
                        <td>{{ $student->birth_date->age }} years</td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $student->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>{{ $student->address ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $student->user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($student->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-book"></i> Enrolled Courses</h5>
            </div>
            <div class="card-body">
                @if($student->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Enrollment Date</th>
                                <th>Average Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->course->course_code }}</td>
                                <td>{{ $enrollment->course->name }}</td>
                                <td>{{ $enrollment->course->credits }}</td>
                                <td>{{ $enrollment->enrollment_date->format('M d, Y') }}</td>
                                <td>
                                    @if($enrollment->grades->count() > 0)
                                        <span class="badge bg-primary">{{ number_format($enrollment->average_grade, 1) }}</span>
                                    @else
                                        <span class="badge bg-secondary">No grades</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $student->enrollments->count() }}</h4>
                                <small class="text-muted">Total Courses</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $student->enrollments->where('is_active', true)->count() }}</h4>
                                <small class="text-muted">Active Enrollments</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $student->enrollments->sum(function($e) { return $e->course->credits; }) }}</h4>
                                <small class="text-muted">Total Credits</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ number_format($student->enrollments->avg('average_grade'), 1) }}</h4>
                                <small class="text-muted">Overall Average</small>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Course Enrollments</h5>
                    <p class="text-muted">This student is not enrolled in any courses yet.</p>
                </div>
                @endif
            </div>
        </div>

        @if($student->enrollments->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Recent Grades</h5>
            </div>
            <div class="card-body">
                @php
                    $recentGrades = $student->enrollments->flatMap(function($enrollment) {
                        return $enrollment->grades;
                    })->sortByDesc('evaluation_date')->take(10);
                @endphp

                @if($recentGrades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Evaluation</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGrades as $grade)
                            <tr>
                                <td>{{ $grade->enrollment->course->course_code }}</td>
                                <td>{{ $grade->evaluation_type }}</td>
                                <td>{{ $grade->score }}/{{ $grade->max_score }}</td>
                                <td>
                                    <span class="badge bg-{{ $grade->percentage >= 70 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger') }}">
                                        {{ number_format($grade->percentage, 1) }}%
                                    </span>
                                </td>
                                <td>{{ $grade->evaluation_date->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                    <p class="text-muted">No grades recorded yet.</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
