@extends('layouts.app')

@section('title', 'New Course')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus"></i> New Course</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('courses.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="course_code" class="form-label">Course Code</label>
                                <input type="text" class="form-control @error('course_code') is-invalid @enderror"
                                       id="course_code" name="course_code" value="{{ old('course_code') }}"
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
                                       id="name" name="name" value="{{ old('name') }}"
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
                                  placeholder="Brief description of the course content and objectives">{{ old('description') }}</textarea>
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
                                       id="credits" name="credits" value="{{ old('credits', '3') }}" required>
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
                                    <option value="Beginner" {{ old('level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="Graduate" {{ old('level') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
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
                                       id="schedule" name="schedule" value="{{ old('schedule') }}"
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
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Course
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> After creating the course, you can assign teachers to it from the course details page.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-lightbulb"></i> Course Creation Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Use clear, descriptive course codes
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Write comprehensive descriptions
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Set appropriate credit hours
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Choose the correct difficulty level
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i>
                        Include specific schedule times
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-examples"></i> Examples</h5>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>Course Code:</strong> CS101, MATH201, ENG102<br>
                    <strong>Schedule:</strong> Mon/Wed/Fri 10:00-11:30<br>
                    <strong>Credits:</strong> Usually 1-4 for undergraduate courses
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
