@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Course</h4>
                <p class="mb-0 text-muted">Course: {{ $course->course_code }} - {{ $course->name }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('courses.update', $course) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="course_code" class="form-label">Course Code</label>
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror"
                                       id="course_code" name="course_code" value="{{ old('course_code', $course->course_code) }}"
                                       placeholder="e.g., CS101, MATH201" required>
                                @error('course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Course Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $course->name) }}"
                                       placeholder="e.g., Introduction to Programming" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Brief description of the course content and objectives">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="credits" class="form-label">Credits</label>
                                <input type="number" min="1" max="10"
                                       class="form-control @error('credits') is-invalid @enderror"
                                       id="credits" name="credits" value="{{ old('credits', $course->credits) }}" required>
                                @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select @error('level') is-invalid @enderror"
                                        id="level" name="level" required>
                                    <option value="">Select Level...</option>
                                    <option value="Beginner" {{ old('level', $course->level) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ old('level', $course->level) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ old('level', $course->level) == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="Graduate" {{ old('level', $course->level) == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                </select>
                                @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="schedule" class="form-label">Schedule</label>
                                <input type="text" class="form-control @error('schedule') is-invalid @enderror"
                                       id="schedule" name="schedule" value="{{ old('schedule', $course->schedule) }}"
                                       placeholder="e.g., Mon/Wed/Fri 10:00-11:30">
                                @error('schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Course
                            </label>
                        </div>
                    </div>

                    @if($course->enrollments->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> This course has {{ $course->enrollments->count() }} enrolled students.
                        Changes may affect their academic records.
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Course Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td>{{ $course->updated_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Enrolled Students:</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ $course->enrollments->count() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Assigned Teachers:</strong></td>
                        <td>
                            <span class="badge bg-success">{{ $course->teachers->count() }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($course->teachers->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-chalkboard-teacher"></i> Assigned Teachers</h5>
            </div>
            <div class="card-body">
                @foreach($course->teachers as $teacher)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $teacher->full_name }}</strong><br>
                        <small class="text-muted">{{ $teacher->specialization }}</small>
                    </div>
                    <span class="badge bg-info">{{ $teacher->teacher_code }}</span>
                </div>
                @if(!$loop->last)<hr>@endif
                @endforeach
            </div>
        </div>
        @endif

        @if($course->enrollments->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-chart-pie"></i> Enrollment Stats</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $course->enrollments->where('is_active', true)->count() }}</h4>
                        <small class="text-muted">Active</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-secondary">{{ $course->enrollments->where('is_active', false)->count() }}</h4>
                        <small class="text-muted">Inactive</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
