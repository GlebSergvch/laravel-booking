<?php

namespace App\Resources\Room;

use App\Models\Room;
use App\Resources\AbstractResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Schema(
 *     schema="RoomListResponse_200",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="hotel_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Deluxe Suite"),
 *     @OA\Property(property="capacity", type="integer", example=2),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
 *     @OA\Property(
 *         property="options",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Wi-Fi")
 *         )
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-05T23:29:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-05T23:29:00Z"),
 *     @OA\Property(property="created_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="updated_by", type="integer", nullable=true, example=1)
 * )
 * @OA\Schema(
 *     schema="RoomListResponse_422",
 *     type="object",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="The given data was invalid."
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "name": {"The name field is required."},
 *             "capacity": {"The capacity must be an integer."},
 *             "price_per_night": {"The price per night must be a number."},
 *             "option_ids": {"The selected option_ids.0 is invalid."}
 *         }
 *     ),
 *     description="Ответ при ошибке валидации данных"
 * )
 */
class RoomListResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Room $room */
        $room = $this->resource;

        // Кэширование данных опций для оптимизации
//        $options = Cache::remember("room:{$room->id}:options", now()->addMinutes(10), function () use ($room) {
//            return $room->options->map(function ($option) {
//                return [
//                    'id' => $option->id,
//                    'name' => $option->name,
//                ];
//            })->toArray();
//        });

        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'price_per_night' => (float) $this->price_per_night, // Приведение к float для соответствия схеме
//            'options' => $options,
            'created_at' => $this->created_at?->format('Y-m-d'), // Формат ISO 8601
            'updated_at' => $this->updated_at?->format('Y-m-d'), // Формат ISO 8601
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
