<?php

namespace App\Http\Api\V1\BookingObject;

use App\DTO\Booking\BookingObjectDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Booking\BookingObjectRequest;
use App\Services\BookingObjectService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CreateBookingObjectController extends AbstractController
{
    public function __construct(private readonly BookingObjectService $bookingObjectService) {}

    /**
     * @OA\Post(
     *      path="/api/v1/booking-objects",
     *      summary="Создание объекта бронирования",
     *      security={{"Bearer": {}}},
     *      tags={"BookingObject"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BookingObject_BookingObjectRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  ref="#/components/schemas/BookingObjectResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Create successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/BookingObjectResponse_422")
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
    public function __invoke(BookingObjectRequest $request): JsonResponse
    {
        $dto = new BookingObjectDto(
            room_id: $request->input('room_id'),
            name: $request->input('name'),
            description: $request->input('description'),
            type: $request->input('type')
        );

        return $this->bookingObjectService->create($dto);
    }
}
