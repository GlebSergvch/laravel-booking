<?php

namespace App\Http\Api\V1\Room;

use App\DTO\Room\RoomDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Room\RoomRequest;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpdateRoomController extends AbstractController
{
    /**
     * @param RoomService $roomService
     */
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/room/{id}",
     *      summary="Обновление номера",
     *      security={{"Bearer": {}}},
     *      tags={"Room"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID номера",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/Room_RoomRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  ref="#/components/schemas/RoomCreateResponse_200"
     *              ),
     *              @OA\Property(property="message", type="string", example="Create successful")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/RoomCreateResponse_422")
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
     * @param RoomRequest $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function __invoke(int $id, RoomRequest $request): JsonResponse
    {
        $dto = new RoomDto(
            id: $id,
            name: $request->input('name'),
            capacity: $request->input('capacity'),
            price_per_night: $request->input('price_per_night'),
            option_ids: $request->input('option_ids', [])
        );

        return $this->roomService->update($dto);
    }
}
