<?php

namespace App\Http\Api\V1\Review;

use App\Http\Api\V1\AbstractController;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ShowReviewController extends AbstractController
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/review/{id}",
     *      summary="Получение информации об отзыве",
     *      security={{"Bearer": {}}},
     *      tags={"Review"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID отзыва",
     *          @OA\Schema(type="integer")
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
     *              @OA\Property(property="message", type="string", example="Read successful")
     *          )
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
     *      )
     * )
     */
    public function __invoke(int $id): JsonResponse
    {
        return $this->reviewService->show($id);
    }
}
