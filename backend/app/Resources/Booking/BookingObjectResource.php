<?php

namespace App\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BookingObjectResponse_200",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="room_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Deluxe Suite"),
 *     @OA\Property(property="description", type="string", example="A luxurious suite with a view", nullable=true),
 *     @OA\Property(property="type", type="string", example="suite"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="created_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="updated_by", type="integer", nullable=true, example=1)
 * )
 * @OA\Schema(
 *     schema="BookingObjectResponse_422",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "room_id": {"The room_id field is required."},
 *             "name": {"The name field is required."},
 *             "type": {"The type field is required."}
 *         }
 *     ),
 *     description="Ответ при ошибке валидации данных"
 * )
 */
class BookingObjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
