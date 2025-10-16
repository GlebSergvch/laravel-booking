<?php
// app/Http/Requests/Booking/BookingRequest.php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => [
                'required',
                'integer',
                'exists:time_slots,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Time slot ID is required',
            'time_slot_id.exists' => 'Selected time slot does not exist',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $timeSlotId = $this->input('time_slot_id');

            // Проверка доступности слота
            $timeSlot = \App\Models\TimeSlot::find($timeSlotId);
            if (!$timeSlot) {
                $validator->errors()->add('time_slot_id', 'Time slot not found');
                return;
            }

            if (!$timeSlot->is_available) {
                $validator->errors()->add('time_slot_id', 'This time slot is not available for booking');
                return;
            }

            // Проверка, что слот не забронирован
            $existingBooking = \App\Models\Booking::where('time_slot_id', $timeSlotId)->first();
            if ($existingBooking) {
                $validator->errors()->add('time_slot_id', 'This time slot is already booked');
                return;
            }
        });
    }
}
