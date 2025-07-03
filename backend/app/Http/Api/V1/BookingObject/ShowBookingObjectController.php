<?php

namespace App\Http\Api\V1\BookingObject;

use App\Http\Api\V1\AbstractController;
use App\Services\BookingObjectService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ShowBookingObjectController extends AbstractController
{
    public function __construct(private readonly BookingObjectService $bookingObjectService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/booking-objects/{id}",
     *      summary="Получение информации об объекте бронирования",
     *      security={{"Bearer": {}}},
     *      tags={"BookingObject"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID объекта бронирования",
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
     *                  ref="#/components/schemas/BookingObjectResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Read successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Booking object not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Booking object not found")
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
        return $this->bookingObjectService->show($id);
    }
}
