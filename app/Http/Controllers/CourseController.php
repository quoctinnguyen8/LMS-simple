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
        $courses = Course::where('status', '!=', 'draft')
            ->where('end_registration_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('courses.index', ['courses' => $courses]);
    }
    public function show($id)
    {
        $course = Course::findOrFail($id);
        if ($course->status === 'draft') {
            return redirect()->route('courses.index')->with('error', 'Khóa học không tồn tại hoặc đã bị xóa.');
        }
        return view('courses.detail')->with('course', $course);
    }

    public function course_registration(CourseRequest $request)
    {
        $validatedData = $request->validated();
        $course_id = $validatedData['course_id'];
        $course = Course::findOrFail($course_id);
        if (!$course->allow_overflow) {
            $existingRegistrations = CourseRegistration::where('course_id', $course_id)
                ->where('status', 'confirmed')
                ->count();
            if ($existingRegistrations >= $course->max_students) {
                return redirect()->back()->with('error', 'Khóa học đã đủ số lượng học viên đăng ký.');
            }
        }
        $registration = new CourseRegistration();
        $registration->course_id = $course_id;
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
        return redirect()->route('courses.show', ['id' => $course_id])->with('success', 'Đăng ký khóa học thành công.');
    }
}
