<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\AbstractApiRequest;
use App\Interfaces\ApiRequestInterface;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Review_ReviewRequest",
 *     required={"booking_object_id", "review", "rating"},
 *     @OA\Property(property="booking_object_id", type="integer"),
 *     @OA\Property(property="review", type="string"),
 *     @OA\Property(property="rating", type="integer")
 * )
 */
class ReviewRequest extends AbstractApiRequest implements ApiRequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_object_id' => 'required|integer|exists:booking_objects,id',
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ];
    }
}
