<?php

namespace App\Http\Api\V1\Room;

use App\Http\Api\V1\AbstractController;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DeleteRoomController extends AbstractController
{
    /**
     * @param RoomService $roomService
     */
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/rooms/{id}",
     *      summary="Удаление номера",
     *      security={{"Bearer": {}}},
     *      tags={"Room"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID номера",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="No Content",
     *      ),
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
        $this->roomService->delete($id);
        return response()->json(null, 204);
    }
}
