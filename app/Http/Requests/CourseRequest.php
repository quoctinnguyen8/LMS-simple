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
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:course_registrations,student_phone,NULL,id,course_id,' . $this->course_id,
            'dob' => 'required|date|before:today|after:1900-01-01',
            'address' => 'nullable|string|min:10|max:500',
            'gender' => 'required|in:male,female,other',
        ];

        // Chỉ thêm reCAPTCHA validation cho form đăng ký khóa học từ frontend
        if (
            config('services.recaptcha.enabled', false) &&
            (request()->is('courses/*/registration') || request()->routeIs('courses.registration'))
        ) {
            $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
        }

        return $rules;
    }
    public function atributes(): array
    {
        return [
            'course_id' => 'Khóa học',
            'name' => 'Họ và tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'dob' => 'Ngày sinh',
            'address' => 'Địa chỉ',
            'gender' => 'Giới tính',
            'g-recaptcha-response' => 'reCAPTCHA',
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Vui lòng chọn khóa học.',
            'course_id.exists' => 'Khóa học không tồn tại.',
            'name.required' => 'Họ và tên là bắt buộc.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.min' => 'Họ và tên phải có ít nhất :min ký tự.',
            'name.max' => 'Họ và tên không được vượt quá :max ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng.',
            'phone.unique' => 'Số điện thoại đã được đăng ký cho khóa học này.',
            'dob.required' => 'Ngày sinh là bắt buộc.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'dob.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'dob.after' => 'Ngày sinh phải sau ngày 01/01/1900.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.min' => 'Địa chỉ phải có ít nhất :min ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ. Vui lòng chọn nam, nữ hoặc khác.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
            'g-recaptcha-response.recaptcha' => 'Xác minh reCAPTCHA không thành công. Vui lòng thử lại.',
        ];
    }
}
