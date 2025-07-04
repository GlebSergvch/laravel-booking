<?php

namespace App\Resources\TimeSlot;

use App\Resources\AbstractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TimeSlotList_200",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="booking_object_id", type="integer", example=1),
 *             @OA\Property(property="start_time", type="string", format="date-time", example="2025-07-10T14:00:00Z"),
 *             @OA\Property(property="end_time", type="string", format="date-time", example="2025-07-12T12:00:00Z"),
 *             @OA\Property(property="is_available", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=1),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=2)
 *     ),
 *     @OA\Property(
 *         property="related",
 *         type="array",
 *         @OA\Items(),
 *         example="[]"
 *     )
 * )
 */
class TimeSlotListResource extends AbstractResource
{
    public function toArray(Request $request): array|Arrayable
    {
        return [
            'id' => $this->id,
            'booking_object_id' => $this->booking_object_id,
            'start_time' => $this->start_time->toIso8601String(),
            'end_time' => $this->end_time->toIso8601String(),
            'is_available' => $this->is_available,
        ];
    }
}
