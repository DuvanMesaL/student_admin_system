@extends('layouts.app')

@section('title', 'Bulk Grade Entry')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-plus-circle"></i> Bulk Grade Entry</h2>
        <p class="text-muted">Course: {{ $course->name }} ({{ $course->course_code }})</p>
    </div>
    <a href="{{ route('grades.index', $course) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('grades.store-bulk', $course) }}" method="POST">
            @csrf

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="evaluation_type" class="form-label">Evaluation Type</label>
                    <select class="form-select @error('evaluation_type') is-invalid @enderror"
                            id="evaluation_type" name="evaluation_type" required>
                        <option value="">Select...</option>
                        <option value="Quiz" {{ old('evaluation_type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="Midterm" {{ old('evaluation_type') == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                        <option value="Final" {{ old('evaluation_type') == 'Final' ? 'selected' : '' }}>Final</option>
                        <option value="Assignment" {{ old('evaluation_type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                        <option value="Project" {{ old('evaluation_type') == 'Project' ? 'selected' : '' }}>Project</option>
                        <option value="Participation" {{ old('evaluation_type') == 'Participation' ? 'selected' : '' }}>Participation</option>
                    </select>
                    @error('evaluation_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="max_score" class="form-label">Maximum Score</label>
                    <input type="number" step="0.01" min="1"
                           class="form-control @error('max_score') is-invalid @enderror"
                           id="max_score" name="max_score" value="{{ old('max_score', '100') }}" required>
                    @error('max_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="evaluation_date" class="form-label">Evaluation Date</label>
                    <input type="date" class="form-control @error('evaluation_date') is-invalid @enderror"
                           id="evaluation_date" name="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" required>
                    @error('evaluation_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if($enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Code</th>
                            <th>Student Name</th>
                            <th>Score</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $index => $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->student_code }}</td>
                            <td>{{ $enrollment->student->full_name }}</td>
                            <td>
                                <input type="hidden" name="grades[{{ $index }}][enrollment_id]" value="{{ $enrollment->id }}">
                                <input type="number" step="0.01" min="0"
                                       class="form-control"
                                       name="grades[{{ $index }}][score]"
                                       value="{{ old('grades.'.$index.'.score') }}"
                                       required>
                            </td>
                            <td>
                                <input type="text" class="form-control"
                                       name="grades[{{ $index }}][comments]"
                                       value="{{ old('grades.'.$index.'.comments') }}"
                                       placeholder="Optional comments">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Save All Grades
                </button>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                <p class="text-muted">No students enrolled in this course.</p>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
