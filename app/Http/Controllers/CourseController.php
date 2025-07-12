<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseRegistration;
use Dotenv\Validator;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('course', ['courses' => $courses]);
    }
    public function show($id)
    {
        $course = Course::findOrFail($id);
        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();
        return view('course-detail')->with('course', $course)
            ->with('relatedCourses', $relatedCourses);
    }

    public function course_registration(CourseRequest $request)
    {
        $validatedData = $request->validated();
        $id = $validatedData['course_id'];
        $registration = new CourseRegistration();
        $registration->course_id = $id;
        $registration->registration_date = now();
        $registration->status = 'pending';
        $registration->payment_status = 'unpaid';
        $registration->student_name = $validatedData['name'];
        $registration->student_phone = $validatedData['phone'];
        $registration->student_email = $validatedData['email'];
        $registration->student_birth_date = $validatedData['dob'];
        $registration->student_address = $validatedData['address'];
        $registration->student_gender = $validatedData['gender'];
        $registration->created_by = Auth::id() ?? null;
        $registration->save();
        return redirect()->route('courses.show', ['id' => $id])->with('success', 'Đăng ký khóa học thành công.');
    }
}
