<?php

namespace App\Http\Api\V1\TimeSlot;

use App\Http\Api\V1\AbstractController;
use App\Services\TimeSlotService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ShowTimeSlotController extends AbstractController
{
    public function __construct(private readonly TimeSlotService $timeSlotService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/time-slot/{id}",
     *      summary="Получение информации о временном слоте",
     *      security={{"Bearer": {}}},
     *      tags={"TimeSlot"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID временного слота",
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
     *                  ref="#/components/schemas/TimeSlotResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Read successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Time slot not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Time slot not found")
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
        return $this->timeSlotService->show($id);
    }
}
