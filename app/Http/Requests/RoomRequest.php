<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RecaptchaRule;
use Carbon\Carbon;

class RoomRequest extends FormRequest
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
            'room_id' => 'required|exists:rooms,id',
            'reason' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:500',
            'repeat_days' => 'array',
            'repeat_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/',
        ];
        if ($this->input('room_type') === 'weekly') {
            $rules['repeat_days'] .= '|required';
            $rules['end_date'] = '|after:start_date';
        } else {
            $rules['repeat_days'] .= '|nullable';
            $rules['end_date'] = '|same:start_date';
            
            $date = Carbon::createFromFormat('Y-m-d', $this->start_date);
            if ($date->isToday()) {
                $rules['start_time'] .= '|after_or_equal:' . now()->addMinutes(30)->format('H:i');
            }
        }
        $room = \App\Models\Room::find($this->input('room_id'));
        if ($room) {
            $rules['participants_count'] = 'required|integer|min:1|max:' . $room->capacity;
        }
        // Chỉ thêm reCAPTCHA validation cho form đặt phòng từ frontend
        if (
            config('services.recaptcha.enabled', false) &&
            request()->is('phong-hoc/*/đat-phong') || request()->routeIs('rooms.bookings')
        ) {
            $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
        }
        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = Carbon::createFromFormat('H:i', $this->start_time);
            $end = Carbon::createFromFormat('H:i', $this->end_time);
            if ($end->hour < 6 && $end->minute < 30 || $end->hour > 23 || ($end->hour == 23 && $end->minute > 0)) {
                $validator->errors()->add('end_time', 'Giờ kết thúc phải từ 6:30 đến 23:00.');
            }
            if ($end->hour == $start->hour && $end->minute - $start->minute < 30) {
                $validator->errors()->add('end_time', 'Thời gian kết thúc phải cách thời gian bắt đầu ít nhất 30 phút.');
            }
            if ($start->hour < 6 || $start->hour > 22) {
                $validator->errors()->add('start_time', 'Giờ bắt đầu phải từ 6:00 đến 22:00.');
            }
        });
    }

    public function atributes(): array
    {
        return [
            'room_id' => 'Phòng',
            'reason' => 'Lý do đặt phòng',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'start_time' => 'Giờ bắt đầu',
            'end_time' => 'Giờ kết thúc',
            'participants_count' => 'Số lượng người tham gia',
            'repeat_days' => 'Ngày lặp lại',
            'notes' => 'Ghi chú',
            'name' => 'Tên khách hàng',
            'email' => 'Email khách hàng',
            'phone' => 'Số điện thoại khách hàng',
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
            'room_id.required' => 'Vui lòng chọn phòng.',
            'room_id.exists' => 'Phòng không tồn tại.',
            'reason.required' => 'Lý do đặt phòng là bắt buộc.',
            'reason.string' => 'Lý do đặt phòng phải là chuỗi ký tự.',
            'reason.max' => 'Lý do đặt phòng không được vượt quá :max ký tự.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là ngày hôm nay hoặc sau.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu khi chọn loại đặt phòng hàng tuần.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.same' => 'Ngày kết thúc phải giống với ngày bắt đầu khi chọn loại đặt phòng theo ngày.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'start_time.date_format' => 'Giờ bắt đầu không đúng định dạng. Vui lòng sử dụng định dạng HH:MM.',
            'start_time.after_or_equal' => 'Giờ bắt đầu phải cách ít nhất 30 phút so với thời điểm hiện tại nếu đặt phòng trong ngày hôm nay.',
            'end_time.required' => 'Giờ kết thúc là bắt buộc.',
            'end_time.date_format' => 'Giờ kết thúc không đúng định dạng. Vui lòng sử dụng định dạng HH:MM.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
            'participants_count.required' => 'Số lượng người tham gia là bắt buộc.',
            'participants_count.integer' => 'Số lượng người tham gia phải là số nguyên.',
            'participants_count.min' => 'Số lượng người tham gia phải lớn hơn hoặc bằng 1.',
            'participants_count.max' => 'Số lượng người tham gia không được vượt quá :max.',
            'repeat_days.required' => 'Ngày lặp lại là bắt buộc khi chọn loại đặt phòng theo tuần.',
            'repeat_days.array' => 'Ngày lặp lại phải là một mảng.',
            'repeat_days.*.string' => 'Ngày lặp lại phải là chuỗi ký tự.',
            'repeat_days.*.in' => 'Ngày lặp lại không hợp lệ. Vui lòng chọn từ thứ Hai đến Chủ Nhật.',
            'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá :max ký tự.',
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'name.max' => 'Tên khách hàng không được vượt quá :max ký tự.',
            'email.required' => 'Email khách hàng là bắt buộc.',
            'email.email' => 'Email khách hàng không hợp lệ.',
            'email.regex' => 'Email khách hàng không hợp lệ. Vui lòng nhập đúng định dạng.',
            'email.max' => 'Email khách hàng không được vượt quá :max ký tự.',
            'phone.required' => 'Số điện thoại khách hàng là bắt buộc.',
            'phone.string' => 'Số điện thoại khách hàng phải là chuỗi ký tự và không được chứa ký tự đặc biệt.',
            'phone.max' => 'Số điện thoại khách hàng không được vượt quá :max ký tự.',
            'phone.regex' => 'Số điện thoại khách hàng không hợp lệ. Vui lòng nhập đúng định dạng.',
            'g-recaptcha-response.required' => 'Vui lòng xác minh bạn không phải là robot.',
            'g-recaptcha-response.recaptcha' => 'Xác minh reCAPTCHA không thành công. Vui lòng thử lại.',
        ];
    }
}
