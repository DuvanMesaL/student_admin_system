@extends('layouts.app')

@section('title', 'Dashboard - Administrator')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-tachometer-alt"></i> Administrator Dashboard</h2>
        <p class="text-muted">Welcome, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $data['total_users'] }}</h4>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
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
                        <h4>{{ $data['total_students'] }}</h4>
                        <p class="mb-0">Students</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x"></i>
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
                        <h4>{{ $data['total_teachers'] }}</h4>
                        <p class="mb-0">Teachers</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
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
                        <h4>{{ $data['total_courses'] }}</h4>
                        <p class="mb-0">Courses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
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
                <h5><i class="fas fa-chart-bar"></i> Quick Statistics</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Active Students</span>
                        <span class="badge bg-success">{{ $data['active_students'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Active Courses</span>
                        <span class="badge bg-info">{{ $data['active_courses'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Enrollments</span>
                        <span class="badge bg-primary">{{ $data['total_enrollments'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tasks"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Student
                    </a>
                    <a href="{{ route('teachers.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> New Teacher
                    </a>
                    <a href="{{ route('courses.create') }}" class="btn btn-info">
                        <i class="fas fa-plus"></i> New Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
