@extends('layouts.app')

@section('title', 'Teacher Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chalkboard-teacher"></i> Teacher Details</h2>
    <div>
        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
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
                        <td><strong>Teacher Code:</strong></td>
                        <td>{{ $teacher->teacher_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Full Name:</strong></td>
                        <td>{{ $teacher->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Document:</strong></td>
                        <td>{{ $teacher->document_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Specialization:</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $teacher->specialization }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $teacher->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $teacher->user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($teacher->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Joined:</strong></td>
                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Teaching Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $teacher->courses->count() }}</h4>
                        <small class="text-muted">Total Courses</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $teacher->courses->where('is_active', true)->count() }}</h4>
                        <small class="text-muted">Active Courses</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ $teacher->courses->sum(function($course) { return $course->enrollments->count(); }) }}</h4>
                        <small class="text-muted">Total Students</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $teacher->courses->sum('credits') }}</h4>
                        <small class="text-muted">Total Credits</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-book"></i> Assigned Courses</h5>
            </div>
            <div class="card-body">
                @if($teacher->courses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Level</th>
                                <th>Students</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teacher->courses as $course)
                            <tr>
                                <td>{{ $course->course_code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->credits }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $course->level }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $course->enrollments->count() }}</span>
                                </td>
                                <td>{{ $course->schedule ?? 'Not set' }}</td>
                                <td>
                                    @if($course->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('courses.show', $course) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('grades.index', $course) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-chart-line"></i>
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
                    <h5 class="text-muted">No Courses Assigned</h5>
                    <p class="text-muted">This teacher is not assigned to any courses yet.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Assign Courses
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if($teacher->courses->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-users"></i> Students Overview</h5>
            </div>
            <div class="card-body">
                @php
                    $allStudents = $teacher->courses->flatMap(function($course) {
                        return $course->enrollments->pluck('student');
                    })->unique('id');
                @endphp

                @if($allStudents->count() > 0)
                <div class="row">
                    @foreach($teacher->courses->take(3) as $course)
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h6 class="card-title">{{ $course->course_code }}</h6>
                                <p class="card-text small">{{ $course->name }}</p>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $course->enrollments->count() }} students</small>
                                    <small class="text-muted">{{ $course->credits }} credits</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($teacher->courses->count() > 3)
                <div class="text-center">
                    <small class="text-muted">And {{ $teacher->courses->count() - 3 }} more courses...</small>
                </div>
                @endif
                @else
                <div class="text-center py-3">
                    <i class="fas fa-users fa-2x text-muted mb-2"></i>
                    <p class="text-muted">No students enrolled in assigned courses.</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
