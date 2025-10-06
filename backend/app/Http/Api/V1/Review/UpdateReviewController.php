<?php

namespace App\Http\Api\V1\Review;

use App\DTO\Review\ReviewDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Review\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class UpdateReviewController extends AbstractController
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * @OA\Put(
     *      path="/api/v1/review/{id}",
     *      summary="Обновление отзыва",
     *      security={{"Bearer": {}}},
     *      tags={"Review"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID отзыва",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Review_ReviewRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  ref="#/components/schemas/ReviewResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Update successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/ReviewResponse_422")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Review not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Review not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized access")
     *          )
     *      )
     * )
     */
    public function __invoke(int $id, ReviewRequest $request): JsonResponse
    {
        $dto = new ReviewDto(
            id: $id,
            booking_object_id: $request->input('booking_object_id'),
            review: $request->input('review'),
            rating: $request->input('rating')
        );

        return $this->reviewService->update($dto);
    }
}
