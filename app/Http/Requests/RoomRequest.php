<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'end_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'repeat_days' => 'nullable|array',
            'repeat_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
        ];
        $room = \App\Models\Room::find($this->input('room_id'));
        if ($room) {
            $rules['participants_count'] = 'required|integer|min:1|max:' . $room->capacity;
        }
        return $rules;
    }
    /**
     * Get custom messages for the validation rules.
     */
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
        ];
    }
}
