<?php

namespace App\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReviewResponse_200",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="booking_object_id", type="integer", example=1),
 *     @OA\Property(property="review", type="string", example="Great stay!"),
 *     @OA\Property(property="rating", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-02T12:00:00Z"),
 *     @OA\Property(property="created_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="updated_by", type="integer", nullable=true, example=1)
 * )
 * @OA\Schema(
 *     schema="ReviewResponse_422",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "booking_object_id": {"The booking_object_id field is required."},
 *             "review": {"The review field is required."},
 *             "rating": {"The rating must be between 1 and 5."}
 *         }
 *     ),
 *     description="Ответ при ошибке валидации данных"
 * )
 */
class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'booking_object_id' => $this->booking_object_id,
            'review' => $this->review,
            'rating' => $this->rating,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
