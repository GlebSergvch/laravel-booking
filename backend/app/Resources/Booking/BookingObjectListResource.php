<?php

namespace App\Resources\Booking;

use App\Resources\AbstractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BookingObjectList_200",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="room_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Deluxe Suite"),
 *             @OA\Property(property="description", type="string", example="A luxurious suite with a view", nullable=true),
 *             @OA\Property(property="type", type="string", example="suite")
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
class BookingObjectListResource extends AbstractResource
{
    public function toArray(Request $request): array|Arrayable
    {
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
        ];
    }
}
