@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4"><i class="fas fa-graduation-cap"></i> Student Administration System</h1>
            <p class="lead">Manage students, teachers, courses, and grades efficiently</p>
            <hr class="my-4">
            <p>A comprehensive system for educational institutions to manage their academic operations.</p>

            @guest
            <div class="mt-4">
                <a class="btn btn-primary btn-lg me-3" href="{{ route('login') }}" role="button">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a class="btn btn-outline-primary btn-lg" href="{{ route('register') }}" role="button">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
            @else
            <div class="mt-4">
                <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" role="button">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
            </div>
            @endguest
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h5>Student Management</h5>
                        <p>Register and manage student information, enrollment, and academic records.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                        <h5>Teacher Management</h5>
                        <p>Manage teacher profiles, course assignments, and academic responsibilities.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5>Grade Management</h5>
                        <p>Track student performance, manage grades, and generate academic reports.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
