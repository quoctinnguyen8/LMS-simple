<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RecaptchaRule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:course_registrations,student_phone',
            'dob' => 'required|date',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,other',
        ];

        // Chỉ thêm reCAPTCHA validation cho form đăng ký khóa học từ frontend
        if (config('services.recaptcha.enabled', false) && 
            (request()->is('courses/*/registration') || request()->routeIs('courses.registration'))) {
            $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
        }

        return $rules;
    }
}
