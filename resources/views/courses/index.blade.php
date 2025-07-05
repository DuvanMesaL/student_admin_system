@extends('layouts.app')

@section('title', 'Course Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book"></i> Course Management</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Course
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Level</th>
                        <th>Credits</th>
                        <th>Teachers</th>
                        <th>Students</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td>
                            <strong>{{ $course->course_code }}</strong>
                        </td>
                        <td>{{ $course->name }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $course->level }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $course->credits }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $course->teachers->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $course->enrollments->count() }}</span>
                        </td>
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
                                <a href="{{ route('courses.edit', $course) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('courses.destroy', $course) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this course? This will affect all enrolled students.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No courses available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $courses->links() }}
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->total() }}</h4>
                        <p class="mb-0">Total Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->where('is_active', true)->count() }}</h4>
                        <p class="mb-0">Active Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-play fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->flatMap->enrollments->count() }}</h4>
                        <p class="mb-0">Total Enrollments</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $courses->sum('credits') }}</h4>
                        <p class="mb-0">Total Credits</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar"></i> Courses by Level</h5>
            </div>
            <div class="card-body">
                @php
                    $levelCounts = $courses->groupBy('level')->map->count();
                @endphp
                @foreach($levelCounts as $level => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $level }}</span>
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-pie"></i> Course Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h5 class="text-success">{{ $courses->where('enrollments_count', '>', 0)->count() }}</h5>
                        <small class="text-muted">With Students</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-info">{{ $courses->where('teachers_count', '>', 0)->count() }}</h5>
                        <small class="text-muted">With Teachers</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning">{{ number_format($courses->avg('credits'), 1) }}</h5>
                        <small class="text-muted">Avg Credits</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
