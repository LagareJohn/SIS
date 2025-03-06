<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminStudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->get();
        return view('admin.students.index', compact('students'));
    }

    public function store(StudentRequest $request)
    {
        $data = $request->validated();
        $data['role'] = 'student';
        $data['is_enrolled'] = false;
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return redirect()->route('admin.students.index')
            ->with('success', 'Student added successfully');
    }

    public function update(StudentRequest $request, User $student)
    {
        $student->update($request->validated());
        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    public function destroy(User $student)
    {
        if ($student->is_enrolled) {
            return back()->with('error', 'Cannot delete enrolled student');
        }
        $student->delete();
        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully');
    }
} 
