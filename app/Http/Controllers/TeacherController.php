<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'teacher_code' => 'required|unique:teachers',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_number' => 'required|unique:teachers',
            'specialization' => 'required|string|max:255',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'teacher_code' => $request->teacher_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'document_number' => $request->document_number,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'courses']);
        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'teacher_code' => 'required|unique:teachers,teacher_code,' . $teacher->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_number' => 'required|unique:teachers,document_number,' . $teacher->id,
            'specialization' => 'required|string|max:255',
            'phone' => 'nullable|string',
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $teacher->update([
            'teacher_code' => $request->teacher_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'document_number' => $request->document_number,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
