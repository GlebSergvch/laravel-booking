<?php

namespace App\Http\Api\V1\Room;

use App\Http\Api\V1\AbstractController;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ShowRoomController extends AbstractController
{
    /**
     * @param RoomService $roomService
     */
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/room/{id}",
     *      summary="Получение информации о номере",
     *      security={{"Bearer": {}}},
     *      tags={"Room"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID номера",
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *            response=200,
     *            description="Success",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="success", type="boolean", example=true),
     *                @OA\Property(
     *                    property="body",
     *                    ref="#/components/schemas/RoomCreateResponse_200"
     *                ),
     *                @OA\Property(property="message", type="string", example="Create successful")
     *            )
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Room not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Room not found")
     *          )
     *      )
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        return $this->roomService->show($id);
    }
}
