<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseRegistration;
use Illuminate\Support\Facades\Auth;
use App\Mail\CourseRegistrationNotification;
use App\Mail\NotifyAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'published')
            ->where('start_date', '>=', now()->subDays(14))
            ->orderBy('created_at', 'desc')
            ->get();
        return view('courses.index', ['courses' => $courses]);
    }
    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->firstOrFail();
        return view('courses.detail')->with('course', $course);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $courses = Course::where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('courses.index')->with('courses', $courses)
            ->with('category', $category);
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
                return redirect()->back()->with('warning', 'Khóa học đã đủ số lượng học viên. Vui lòng chọn khóa học khác hoặc liên hệ với chúng tôi để biết thêm thông tin.');
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
        $registration->created_by = Auth::id() ?? null;
        $registration->save();
        Mail::to($registration->student_email)->send(new CourseRegistrationNotification($registration));    
        $adminUsers = User::where('role', '!=' , 'user')->get();
        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new NotifyAdmin('registration', $registration));
        }
        return redirect()->route('courses.show', ['slug' => $course->slug])->with('success', 'Đăng ký khóa học thành công.');
    }
}
