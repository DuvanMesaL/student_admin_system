@extends('layouts.app')

@section('title', 'Edit Grade')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Grade</h4>
                <p class="mb-0 text-muted">
                    Course: {{ $grade->enrollment->course->name }} | Student: {{ $grade->enrollment->student->full_name }}
                </p>
            </div>
            <div class="card-body">
                <form action="{{ route('grades.update', $grade) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evaluation_type" class="form-label">Evaluation Type</label>
                                <select class="form-select @error('evaluation_type') is-invalid @enderror"
                                        id="evaluation_type" name="evaluation_type" required>
                                    <option value="">Select...</option>
                                    <option value="Quiz" {{ old('evaluation_type', $grade->evaluation_type) == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="Midterm" {{ old('evaluation_type', $grade->evaluation_type) == 'Midterm' ? 'selected' : '' }}>Midterm</option>
                                    <option value="Final" {{ old('evaluation_type', $grade->evaluation_type) == 'Final' ? 'selected' : '' }}>Final</option>
                                    <option value="Assignment" {{ old('evaluation_type', $grade->evaluation_type) == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                    <option value="Project" {{ old('evaluation_type', $grade->evaluation_type) == 'Project' ? 'selected' : '' }}>Project</option>
                                    <option value="Participation" {{ old('evaluation_type', $grade->evaluation_type) == 'Participation' ? 'selected' : '' }}>Participation</option>
                                </select>
                                @error('evaluation_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="evaluation_date" class="form-label">Evaluation Date</label>
                                <input type="date" class="form-control @error('evaluation_date') is-invalid @enderror"
                                       id="evaluation_date" name="evaluation_date"
                                       value="{{ old('evaluation_date', $grade->evaluation_date->format('Y-m-d')) }}" required>
                                @error('evaluation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="score" class="form-label">Score</label>
                                <input type="number" step="0.01" min="0"
                                       class="form-control @error('score') is-invalid @enderror"
                                       id="score" name="score" value="{{ old('score', $grade->score) }}" required>
                                @error('score')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_score" class="form-label">Maximum Score</label>
                                <input type="number" step="0.01" min="1"
                                       class="form-control @error('max_score') is-invalid @enderror"
                                       id="max_score" name="max_score" value="{{ old('max_score', $grade->max_score) }}" required>
                                @error('max_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comments" class="form-label">Comments (Optional)</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror"
                                  id="comments" name="comments" rows="3">{{ old('comments', $grade->comments) }}</textarea>
                        @error('comments')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <strong>Current Percentage:</strong> {{ number_format($grade->percentage, 1) }}%
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('grades.index', $grade->enrollment->course) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Grade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
