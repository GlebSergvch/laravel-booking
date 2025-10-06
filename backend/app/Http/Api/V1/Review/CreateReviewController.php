<?php

namespace App\Http\Api\V1\Review;

use App\DTO\Review\ReviewDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Review\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v1/review",
 *     summary="Создание отзыва",
 *     security={{"Bearer": {}}},
 *     tags={"Review"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Review_ReviewRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="body", ref="#/components/schemas/ReviewResponse_200"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
class CreateReviewController extends AbstractController
{
    public function __construct(private readonly ReviewService $reviewService) {}

    public function __invoke(ReviewRequest $request): JsonResponse
    {
        $dto = new ReviewDto(
            booking_object_id: $request->input('booking_object_id'),
            review: $request->input('review'),
            rating: $request->input('rating')
        );
        return $this->reviewService->create($dto);
    }
}
