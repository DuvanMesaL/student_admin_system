@extends('layouts.app')

@section('title', 'Course Grades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-chart-line"></i> Course Grades</h2>
        <p class="text-muted">{{ $course->name }} ({{ $course->course_code }}) - {{ $course->credits }} Credits</p>
    </div>
    <a href="{{ route('my-grades') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to All Grades
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $grades->count() }}</h4>
                        <p class="mb-0">Total Grades</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
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
                        <h4>{{ $grades->count() > 0 ? number_format($enrollment->average_grade, 1) : '0' }}</h4>
                        <p class="mb-0">Average Score</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-bar fa-2x"></i>
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
                        <h4>{{ $grades->count() > 0 ? number_format(($enrollment->average_grade / 100) * 100, 1) . '%' : '0%' }}</h4>
                        <p class="mb-0">Average Percentage</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-list"></i> Grade History</h5>
    </div>
    <div class="card-body">
        @if($grades->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Evaluation Type</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Date</th>
                        <th>Comments</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $grade)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $grade->evaluation_type }}</span>
                        </td>
                        <td>
                            <strong>{{ $grade->score }}</strong> / {{ $grade->max_score }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $grade->percentage >= 70 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger') }}">
                                {{ number_format($grade->percentage, 1) }}%
                            </span>
                        </td>
                        <td>{{ $grade->evaluation_date->format('M d, Y') }}</td>
                        <td>{{ $grade->comments ?? '-' }}</td>
                        <td>
                            @if($grade->percentage >= 70)
                                <span class="badge bg-success">Passed</span>
                            @elseif($grade->percentage >= 60)
                                <span class="badge bg-warning">Needs Improvement</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h6>Grade Distribution:</h6>
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h5 class="text-success">{{ $grades->where('percentage', '>=', 90)->count() }}</h5>
                        <small class="text-muted">A (90-100%)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h5 class="text-info">{{ $grades->whereBetween('percentage', [80, 89.99])->count() }}</h5>
                        <small class="text-muted">B (80-89%)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h5 class="text-warning">{{ $grades->whereBetween('percentage', [70, 79.99])->count() }}</h5>
                        <small class="text-muted">C (70-79%)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h5 class="text-danger">{{ $grades->where('percentage', '<', 70)->count() }}</h5>
                        <small class="text-muted">F (<70%)</small>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Grades Yet</h4>
            <p class="text-muted">No grades have been recorded for this course.</p>
        </div>
        @endif
    </div>
</div>
@endsection
