@extends('layouts.app')

@section('title', 'My Grades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-line"></i> My Grades</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if($enrollments->count() > 0)
<div class="row">
    @foreach($enrollments as $enrollment)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $enrollment->course->name }}</h5>
                <small class="text-muted">{{ $enrollment->course->course_code }} - {{ $enrollment->course->credits }} Credits</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $enrollment->grades->count() }}</h4>
                            <small class="text-muted">Total Grades</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            @if($enrollment->grades->count() > 0)
                                <h4 class="text-success">{{ number_format($enrollment->average_grade, 1) }}</h4>
                                <small class="text-muted">Average</small>
                            @else
                                <h4 class="text-muted">-</h4>
                                <small class="text-muted">No grades</small>
                            @endif
                        </div>
                    </div>
                </div>

                @if($enrollment->grades->count() > 0)
                <hr>
                <h6>Recent Grades:</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Score</th>
                                <th>%</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollment->grades->sortByDesc('evaluation_date')->take(3) as $grade)
                            <tr>
                                <td>{{ $grade->evaluation_type }}</td>
                                <td>{{ $grade->score }}/{{ $grade->max_score }}</td>
                                <td>
                                    <span class="badge bg-{{ $grade->percentage >= 70 ? 'success' : ($grade->percentage >= 60 ? 'warning' : 'danger') }}">
                                        {{ number_format($grade->percentage, 1) }}%
                                    </span>
                                </td>
                                <td>{{ $grade->evaluation_date->format('M d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('grades.course-grades', $enrollment->course) }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> View All Grades
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">No Courses Enrolled</h4>
        <p class="text-muted">You are not enrolled in any courses yet.</p>
    </div>
</div>
@endif
@endsection
