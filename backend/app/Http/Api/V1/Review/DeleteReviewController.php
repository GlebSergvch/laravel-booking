<?php

namespace App\Http\Api\V1\Review;

use App\Http\Api\V1\AbstractController;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DeleteReviewController extends AbstractController
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * @OA\Delete(
     *      path="/api/v1/review/{id}",
     *      summary="Удаление отзыва",
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
     *              @OA\Property(property="body", type="null"),
     *              @OA\Property(property="message", type="string", example="Delete successful")
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
    public function __invoke(int $id): JsonResponse
    {
        return $this->reviewService->delete($id);
    }
}
