<?php

namespace App\Http\Requests\TimeSlot;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TimeSlot_TimeSlotRequest",
 *     type="object",
 *     required={"booking_object_id", "start_time", "end_time", "is_available"},
 *     @OA\Property(
 *         property="booking_object_id",
 *         type="integer",
 *         example=1,
 *         description="ID объекта бронирования"
 *     ),
 *     @OA\Property(
 *         property="start_time",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-10 14:00:00",
 *         description="Время начала слота"
 *     ),
 *     @OA\Property(
 *         property="end_time",
 *         type="string",
 *         format="date-time",
 *         example="2025-07-12 12:00:00",
 *         description="Время окончания слота"
 *     ),
 *     @OA\Property(
 *         property="is_available",
 *         type="boolean",
 *         example=true,
 *         description="Доступность слота"
 *     ),
 *     description="Данные для создания или обновления временного слота"
 * )
 */
class TimeSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
//        return auth()->user()->hasRole(['admin', 'manager']);
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_object_id' => 'required|integer|exists:booking_objects,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'is_available' => 'required|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $startTime = $this->input('start_time');
            $endTime = $this->input('end_time');
            $bookingObjectId = $this->input('booking_object_id');
            $id = $this->route('id');

            if ($startTime && $endTime && $bookingObjectId) {
                $overlappingSlots = \App\Models\TimeSlot::where('booking_object_id', $bookingObjectId)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('start_time', [$startTime, $endTime])
                            ->orWhereBetween('end_time', [$startTime, $endTime])
                            ->orWhere(function ($query) use ($startTime, $endTime) {
                                $query->where('start_time', '<=', $startTime)
                                    ->where('end_time', '>=', $endTime);
                            });
                    })
                    ->when($id, function ($query, $id) {
                        $query->where('id', '!=', $id);
                    })
                    ->exists();

                if ($overlappingSlots) {
                    $validator->errors()->add('start_time', 'The time slot overlaps with an existing slot.');
                }
            }
        });
    }
}
