<?php

namespace App\Resources\TimeSlot;

use App\Resources\AbstractResource;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="TimeSlotAvailabilityResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="booking_object_id", type="integer", example=5),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-01-15 10:00:00"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-01-16 10:00:00"),
 *     @OA\Property(property="is_available", type="boolean", example=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_by", type="integer", example=2)
 * )
 */
class TimeSlotAvailabilityResource extends AbstractResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_object_id' => $this->booking_object_id,
            'start_time' => $this->start_time->format('Y-m-d H:i:s'),
            'end_time' => $this->end_time->format('Y-m-d H:i:s'),
            'is_available' => (bool) $this->is_available,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'updated_by' => $this->updated_by,
        ];
    }
}
