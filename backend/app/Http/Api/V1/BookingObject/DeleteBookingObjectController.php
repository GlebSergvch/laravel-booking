<?php

namespace App\Http\Api\V1\BookingObject;

use App\Http\Api\V1\AbstractController;
use App\Services\BookingObjectService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DeleteBookingObjectController extends AbstractController
{
    public function __construct(private readonly BookingObjectService $bookingObjectService) {}

    /**
     * @OA\Delete(
     *      path="/api/v1/booking-objects/{id}",
     *      summary="Удаление объекта бронирования",
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
     *              @OA\Property(property="body", type="null"),
     *              @OA\Property(property="message", type="string", example="Delete successful")
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
        return $this->bookingObjectService->delete($id);
    }
}
