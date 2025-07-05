@extends('layouts.app')

@section('title', 'Course Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-book"></i> Course Details</h2>
        <p class="text-muted">{{ $course->course_code }} - {{ $course->name }}</p>
    </div>
    <div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endif
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Course Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Course Code:</strong></td>
                        <td>{{ $course->course_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Course Name:</strong></td>
                        <td>{{ $course->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Credits:</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $course->credits }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Level:</strong></td>
                        <td>
                            <span class="badge bg-secondary">{{ $course->level }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Schedule:</strong></td>
                        <td>{{ $course->schedule ?? 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($course->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Course Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $course->enrollments->count() }}</h4>
                        <small class="text-muted">Total Students</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $course->teachers->count() }}</h4>
                        <small class="text-muted">Assigned Teachers</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ $course->enrollments->where('is_active', true)->count() }}</h4>
                        <small class="text-muted">Active Enrollments</small>
                    </div>
                    <div class="col-6">
                        @php
                            $totalGrades = $course->enrollments->flatMap->grades->count();
                        @endphp
                        <h4 class="text-warning">{{ $totalGrades }}</h4>
                        <small class="text-muted">Total Grades</small>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-cog"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                        <i class="fas fa-user-plus"></i> Assign Teacher
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                        <i class="fas fa-user-graduate"></i> Enroll Student
                    </button>
                    @if($course->teachers->count() > 0)
                    <a href="{{ route('grades.index', $course) }}" class="btn btn-info">
                        <i class="fas fa-chart-line"></i> Manage Grades
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        @if($course->description)
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-file-alt"></i> Course Description</h5>
            </div>
            <div class="card-body">
                <p>{{ $course->description }}</p>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chalkboard-teacher"></i> Assigned Teachers</h5>
            </div>
            <div class="card-body">
                @if($course->teachers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Teacher Code</th>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->teacher_code }}</td>
                                <td>{{ $teacher->full_name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $teacher->specialization }}</span>
                                </td>
                                <td>{{ $teacher->user->email }}</td>
                                <td>
                                    @if($teacher->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teachers.show', $teacher) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                        <form action="{{ route('courses.remove-teacher', [$course, $teacher]) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Remove this teacher from the course?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Teachers Assigned</h5>
                    <p class="text-muted">This course doesn't have any teachers assigned yet.</p>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                        <i class="fas fa-plus"></i> Assign Teacher
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-user-graduate"></i> Enrolled Students</h5>
            </div>
            <div class="card-body">
                @if($course->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Enrollment Date</th>
                                <th>Grades</th>
                                <th>Average</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($course->enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->student_code }}</td>
                                <td>{{ $enrollment->student->full_name }}</td>
                                <td>{{ $enrollment->student->user->email }}</td>
                                <td>{{ $enrollment->enrollment_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $enrollment->grades->count() }}</span>
                                </td>
                                <td>
                                    @if($enrollment->grades->count() > 0)
                                        <span class="badge bg-{{ $enrollment->average_grade >= 70 ? 'success' : ($enrollment->average_grade >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($enrollment->average_grade, 1) }}
                                        </span>
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('students.show', $enrollment->student) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->isTeacher() && auth()->user()->teacher->courses->contains($course))
                                        <a href="{{ route('grades.create', [$course, $enrollment]) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($course->enrollments->count() > 0)
                <div class="mt-4">
                    <h6>Enrollment Statistics:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h5 class="text-success">{{ $course->enrollments->where('is_active', true)->count() }}</h5>
                                <small class="text-muted">Active Students</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @php
                                    $avgGrade = $course->enrollments->avg('average_grade');
                                @endphp
                                <h5 class="text-info">{{ $avgGrade ? number_format($avgGrade, 1) : '0' }}</h5>
                                <small class="text-muted">Class Average</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @php
                                    $passingStudents = $course->enrollments->filter(function($enrollment) {
                                        return $enrollment->average_grade >= 70;
                                    })->count();
                                @endphp
                                <h5 class="text-success">{{ $passingStudents }}</h5>
                                <small class="text-muted">Passing Students</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @php
                                    $failingStudents = $course->enrollments->filter(function($enrollment) {
                                        return $enrollment->grades->count() > 0 && $enrollment->average_grade < 70;
                                    })->count();
                                @endphp
                                <h5 class="text-danger">{{ $failingStudents }}</h5>
                                <small class="text-muted">At Risk Students</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Students Enrolled</h5>
                    <p class="text-muted">This course doesn't have any enrolled students yet.</p>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                        <i class="fas fa-plus"></i> Enroll Student
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Teacher to Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('courses.assign-teacher', $course) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Select Teacher</label>
                        <select class="form-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Choose a teacher...</option>
                            @foreach(\App\Models\Teacher::where('is_active', true)->whereNotIn('id', $course->teachers->pluck('id'))->get() as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->full_name }} ({{ $teacher->specialization }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enroll Student Modal -->
<div class="modal fade" id="enrollStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enroll Student in Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('courses.enroll-student', $course) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Choose a student...</option>
                            @foreach(\App\Models\Student::where('is_active', true)->whereNotIn('id', $course->enrollments->pluck('student_id'))->get() as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->full_name }} ({{ $student->student_code }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="enrollment_date" class="form-label">Enrollment Date</label>
                        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
