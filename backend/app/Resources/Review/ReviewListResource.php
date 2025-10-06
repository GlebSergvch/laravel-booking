<?php

namespace App\Resources\Review;

use App\Resources\AbstractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReviewList_200",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="booking_object_id", type="integer", example=1),
 *             @OA\Property(property="review", type="string", example="Great stay!"),
 *             @OA\Property(property="rating", type="integer", example=5)
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=1),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=10)
 *     ),
 *     @OA\Property(
 *         property="related",
 *         type="array",
 *         @OA\Items(),
 *         example="[]"
 *     )
 * )
 */
class ReviewListResource extends AbstractResource
{
    public function toArray(Request $request): array|Arrayable
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'booking_object_id' => $this->booking_object_id,
            'review' => $this->review,
            'rating' => $this->rating,
        ];
    }
}
