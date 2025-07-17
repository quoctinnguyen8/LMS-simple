<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RecaptchaRule;

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
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'participants_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'repeat_days' => 'nullable|array',
            'repeat_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
        ];

        // Chỉ thêm reCAPTCHA validation cho form đặt phòng từ frontend
        if (config('services.recaptcha.enabled', false) && 
            request()->is('rooms/*/bookings') || request()->routeIs('rooms.bookings')) {
            $rules['g-recaptcha-response'] = ['required', new RecaptchaRule()];
        }

        return $rules;
    }
}
