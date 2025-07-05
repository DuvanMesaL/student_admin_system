@extends('layouts.app')

@section('title', 'Grades Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-chart-line"></i> Grades Management</h2>
        <p class="text-muted">Course: {{ $course->name }} ({{ $course->course_code }})</p>
    </div>
    <div>
        <a href="{{ route('grades.bulk-grade', $course) }}" class="btn btn-success me-2">
            <i class="fas fa-plus-circle"></i> Bulk Grade
        </a>
        <a href="{{ route('my-courses') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($enrollments->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Code</th>
                        <th>Student Name</th>
                        <th>Total Grades</th>
                        <th>Average Grade</th>
                        <th>Last Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                    <tr>
                        <td>{{ $enrollment->student->student_code }}</td>
                        <td>{{ $enrollment->student->full_name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $enrollment->grades->count() }}</span>
                        </td>
                        <td>
                            @if($enrollment->grades->count() > 0)
                                <span class="badge bg-primary">{{ number_format($enrollment->average_grade, 1) }}</span>
                            @else
                                <span class="badge bg-secondary">No grades</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->grades->count() > 0)
                                {{ $enrollment->grades->sortByDesc('evaluation_date')->first()->score }}/{{ $enrollment->grades->sortByDesc('evaluation_date')->first()->max_score }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('grades.create', [$course, $enrollment]) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Grade
                                </a>
                                @if($enrollment->grades->count() > 0)
                                <button type="button" class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#gradesModal{{ $enrollment->id }}">
                                    <i class="fas fa-eye"></i> View All
                                </button>
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
            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
            <p class="text-muted">No students enrolled in this course.</p>
        </div>
        @endif
    </div>
</div>

<!-- Modals for viewing grades -->
@foreach($enrollments as $enrollment)
@if($enrollment->grades->count() > 0)
<div class="modal fade" id="gradesModal{{ $enrollment->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grades for {{ $enrollment->student->full_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Evaluation Type</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Date</th>
                                <th>Comments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollment->grades->sortByDesc('evaluation_date') as $grade)
                            <tr>
                                <td>{{ $grade->evaluation_type }}</td>
                                <td>{{ $grade->score }}/{{ $grade->max_score }}</td>
                                <td>
                                    <span class="badge bg-{{ $grade->percentage >= 70 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger') }}">
                                        {{ number_format($grade->percentage, 1) }}%
                                    </span>
                                </td>
                                <td>{{ $grade->evaluation_date->format('M d, Y') }}</td>
                                <td>{{ $grade->comments ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('grades.edit', $grade) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('grades.destroy', $grade) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this grade?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
