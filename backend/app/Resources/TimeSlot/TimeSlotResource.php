<?php

namespace App\Resources\TimeSlot;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TimeSlotResponse_200",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_object_id", type="integer", example=1),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-07-10T14:00:00Z"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-07-12T12:00:00Z"),
 *     @OA\Property(property="is_available", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="created_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="updated_by", type="integer", nullable=true, example=1)
 * )
 * @OA\Schema(
 *     schema="TimeSlotResponse_422",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "booking_object_id": {"The booking_object_id field is required."},
 *             "start_time": {"The start_time field is required."},
 *             "end_time": {"The end_time must be a date after start_time."}
 *         }
 *     ),
 *     description="Ответ при ошибке валидации данных"
 * )
 */
class TimeSlotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_object_id' => $this->booking_object_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_available' => $this->is_available,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
