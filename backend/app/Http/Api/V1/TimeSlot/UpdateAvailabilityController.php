<?php

namespace App\Http\Api\V1\TimeSlot;

use App\DTO\TimeSlot\UpdateAvailabilityDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\TimeSlot\UpdateAvailabilityRequest;
use App\Services\TimeSlotService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class UpdateAvailabilityController extends AbstractController
{
    public function __construct(
        private readonly TimeSlotService $timeSlotService
    ) {}

    /**
     * @OA\Patch(
     *     path="/api/v1/time-slot/{id}/availability",
     *     summary="Обновление доступности временного слота",
     *     description="Меняет только поле is_available. Доступно только для admin/manager",
     *     security={{"Bearer": {}}},
     *     tags={"TimeSlot"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID временного слота"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"is_available"},
     *             @OA\Property(property="is_available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Доступность успешно обновлена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="body", ref="#/components/schemas/TimeSlotAvailabilityResource"),
     *             @OA\Property(property="message", type="string", example="Time slot availability updated")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Нет прав"),
     *     @OA\Response(response=404, description="Time slot not found"),
     *     @OA\Response(
     *         response=422,
     *         description="Валидация или бизнес-ошибка",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function __invoke(UpdateAvailabilityRequest $request, int $id): JsonResponse
    {
        $dto = new UpdateAvailabilityDto(
            id: $id,
            is_available: $request->boolean('is_available'),
            updated_by: auth()->id()
        );

        return $this->timeSlotService->updateAvailability($dto);
    }
}
