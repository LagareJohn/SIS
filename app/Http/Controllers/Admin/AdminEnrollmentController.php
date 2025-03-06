<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\User;

class AdminEnrollmentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->get();
        $subjects = Subject::all();
        $enrollments = Enrollment::with(['student', 'subject'])->get();
        return view('admin.enrollments.index', compact('enrollments', 'students', 'subjects'));
    }

    public function getStudentsByCourse($course)
    {
        $students = User::where('role', 'student')
            ->where('course', $course)
            ->get(['id', 'name', 'student_id', 'year_level']);
        return response()->json($students);
    }

    public function getSubjectsByCourse($course, $yearLevel)
    {
        $subjects = Subject::where('course', $course)
            ->where('year_level', $yearLevel)
            ->get(['id', 'code', 'name', 'units']);
        return response()->json($subjects);
    }

    public function getStudent($studentId)
    {
        $student = User::where('student_id', $studentId)
            ->where('role', 'student')
            ->first(['id', 'name', 'course', 'year_level', 'student_id']);
        
        return response()->json($student);
    }

    public function getSubject($subjectCode)
    {
        $subject = Subject::where('code', $subjectCode)
            ->first(['id', 'code', 'name', 'units', 'course', 'year_level']);
        
        return response()->json($subject);
    }

    public function store(EnrollmentRequest $request)
    {
        Enrollment::create($request->validated());
        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment created successfully');
    }

    public function destroy(Enrollment $enrollment)
    {
        $userId = $enrollment->user_id;
        
        // Delete the enrollment
        $enrollment->delete();
        
        // Update student enrollment status if they have no more enrollments
        $hasOtherEnrollments = Enrollment::where('user_id', $userId)->exists();
        if (!$hasOtherEnrollments) {
            User::where('id', $userId)->update(['is_enrolled' => false]);
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment cancelled successfully');
    }

    public function searchStudents($search)
    {
        $students = User::where('role', 'student')
            ->where(function($query) use ($search) {
                $query->where('student_id', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'student_id', 'name', 'course', 'year_level']);
        
        return response()->json($students);
    }

    public function searchSubjects($search)
    {
        $subjects = Subject::where('code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'code', 'name', 'units', 'course', 'year_level']);
        
        return response()->json($subjects);
    }

    public function getSubjectsByStudent($searchTerm, $studentId)
    {
        $enrollments = Enrollment::with('subject')
            ->where('user_id', $studentId)
            ->whereHas('subject', function($query) use ($searchTerm) {
                $query->where('code', 'like', "%{$searchTerm}%")
                      ->orWhere('name', 'like', "%{$searchTerm}%");
            })
            ->get();

        return response()->json($enrollments);
    }
}
