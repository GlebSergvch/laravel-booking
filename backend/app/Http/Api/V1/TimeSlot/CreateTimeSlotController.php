<?php

namespace App\Http\Api\V1\TimeSlot;

use App\DTO\TimeSlot\TimeSlotDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\TimeSlot\TimeSlotRequest;
use App\Services\TimeSlotService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CreateTimeSlotController extends AbstractController
{
    public function __construct(private readonly TimeSlotService $timeSlotService) {}

    /**
     * @OA\Post(
     *      path="/api/v1/time-slot",
     *      summary="Создание временного слотаd",
     *      security={{"Bearer": {}}},
     *      tags={"TimeSlot"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TimeSlot_TimeSlotRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  ref="#/components/schemas/TimeSlotResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Create successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/TimeSlotResponse_422")
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
    public function __invoke(TimeSlotRequest $request): JsonResponse
    {
        $dto = new TimeSlotDto(
            booking_object_id: $request->input('booking_object_id'),
            start_time: $request->input('start_time'),
            end_time: $request->input('end_time'),
            is_available: $request->input('is_available')
        );

        return $this->timeSlotService->create($dto);
    }
}
