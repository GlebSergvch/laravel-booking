<?php

namespace App\Http\Api\V1\Booking;

use App\DTO\Booking\BookingDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Booking\BookingRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CreateBookingController extends AbstractController
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/bookings",
     *     summary="Создание бронирования",
     *     description="Создает новое бронирование для доступного временного слота",
     *     security={{"Bearer": {}}},
     *     tags={"Booking"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="time_slot_id",
     *                 type="integer",
     *                 example=1,
     *                 description="ID доступного временного слота"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Бронирование успешно создано",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="body",
     *                 @OA\Property(
     *                     property="id", type="integer", example=1
     *                 ),
     *                 @OA\Property(
     *                     property="user_id", type="integer", example=1
     *                 ),
     *                 @OA\Property(
     *                     property="time_slot_id", type="integer", example=1
     *                 ),
     *                 @OA\Property(
     *                     property="status", type="string", example="pending"
     *                 ),
     *                 @OA\Property(
     *                     property="time_slot",
     *                     type="object",
     *                     @OA\Property(property="start_time", type="string", example="2024-01-15T10:00:00Z"),
     *                     @OA\Property(property="end_time", type="string", example="2024-01-16T10:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Booking created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="time_slot_id",
     *                     type="array",
     *                     @OA\Items(type="string", example="This time slot is not available for booking")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован"
     *     )
     * )
     */
    public function __invoke(BookingRequest $request): JsonResponse
    {
        $dto = new BookingDto(
            time_slot_id: $request->input('time_slot_id'),
            user_id: auth()->id()
        );

        return $this->bookingService->create($dto);
    }
}
