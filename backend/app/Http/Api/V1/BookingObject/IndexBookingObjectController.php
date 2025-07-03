<?php

namespace App\Http\Api\V1\BookingObject;

use App\DTO\Booking\BookingObjectDto;
use App\Http\Api\V1\AbstractController;
use App\Services\BookingObjectService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class IndexBookingObjectController extends AbstractController
{
    public function __construct(private readonly BookingObjectService $bookingObjectService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/booking-objects",
     *      summary="Список объектов бронирования с пагинацией",
     *      security={{"Bearer": {}}},
     *      tags={"BookingObject"},
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
     *                  @OA\Items(ref="#/components/schemas/BookingObjectList_200")
     *              ),
     *              @OA\Property(property="message", type="string", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid.")
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
        $dto = new BookingObjectDto(
            page: request()->query('page', 1),
            per_page: request()->query('per_page', 15)
        );

        return $this->bookingObjectService->read($dto->per_page);
    }
}
