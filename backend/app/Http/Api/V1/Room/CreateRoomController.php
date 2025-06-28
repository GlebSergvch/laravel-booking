<?php

namespace App\Http\Api\V1\Room;

use App\DTO\Room\RoomDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Room\RoomRequest;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CreateRoomController extends AbstractController
{
    /**
     * @param RoomService $roomService
     */
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/room",
     *      summary="Создание номера в отеле",
     *      security={{"Bearer": {}}},
     *      tags={"Room"},
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/Room_RoomRequest")
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="Success",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="success", type="boolean", example=true),
     *               @OA\Property(
     *                   property="body",
     *                   ref="#/components/schemas/RoomCreateResponse_200"
     *               ),
     *               @OA\Property(property="message", type="string", example="Create successful")
     *           )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/RoomCreateResponse_422")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Hotel not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Hotel not found")
     *          )
     *      )
     * )
     *
     * @param RoomRequest $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function __invoke(RoomRequest $request): JsonResponse
    {
        $dto = new RoomDto(
            hotel_id: $request->input('hotel_id'),
            name: $request->input('name'),
            capacity: $request->input('capacity'),
            price_per_night: $request->input('price_per_night'),
            option_ids: $request->input('option_ids', []) // Опционально, массив ID опций
        );

        return $this->roomService->create($dto);
    }
}
