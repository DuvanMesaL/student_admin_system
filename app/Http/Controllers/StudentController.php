<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'student_code' => 'required|unique:students',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_number' => 'required|unique:students',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_code' => $request->student_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'document_number' => $request->document_number,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'enrollments.course', 'enrollments.grades']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_code' => 'required|unique:students,student_code,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_number' => 'required|unique:students,document_number,' . $student->id,
            'birth_date' => 'required|date',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'student_code' => $request->student_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'document_number' => $request->document_number,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
