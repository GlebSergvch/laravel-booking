<?php

namespace App\Http\Api\V1\Review;

use App\DTO\Review\ReviewDto;
use App\Http\Api\V1\AbstractController;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class IndexReviewController extends AbstractController
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/review",
     *      summary="Список отзывов с пагинацией",
     *      security={{"Bearer": {}}},
     *      tags={"Review"},
     *      @OA\Parameter(
     *          name="booking_object_id",
     *          in="query",
     *          description="ID объекта бронирования",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="hotel_id",
     *          in="query",
     *          description="ID отеля",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Номер страницы",
     *          required=false,
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Количество элементов на странице",
     *          required=false,
     *          @OA\Schema(type="integer", default=15)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ReviewList_200")
     *              ),
     *              @OA\Property(property="message", type="string", example="")
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
    public function __invoke(): JsonResponse
    {
        $dto = new ReviewDto(
            booking_object_id: request()->query('booking_object_id'),
            hotel_id: request()->query('hotel_id'),
            page: request()->query('page', 1),
            per_page: request()->query('per_page', 15)
        );

        return $this->reviewService->read($dto);
    }
}
