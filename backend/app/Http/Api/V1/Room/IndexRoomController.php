<?php

namespace App\Http\Api\V1\Room;

use App\DTO\Room\RoomDto;
use App\Http\Api\V1\AbstractController;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class IndexRoomController extends AbstractController
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
     *      path="/api/v1/room",
     *      summary="Список номеров отеля",
     *      security={{"Bearer": {}}},
     *      tags={"Room"},
     *      @OA\Parameter(
     *          name="hotel_id",
     *          in="path",
     *          required=true,
     *          description="ID отеля",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="capacity",
     *          in="query",
     *          required=false,
     *          description="Фильтр по вместимости номера",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="price_min",
     *          in="query",
     *          required=false,
     *          description="Минимальная цена за ночь",
     *          @OA\Schema(type="number", format="float")
     *      ),
     *      @OA\Parameter(
     *          name="price_max",
     *          in="query",
     *          required=false,
     *          description="Максимальная цена за ночь",
     *          @OA\Schema(type="number", format="float")
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Номер страницы",
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          required=false,
     *          description="Количество записей на странице",
     *          @OA\Schema(type="integer", default=15)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/RoomCreateResponse_200")
     *          )
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
     * @param int $hotel_id
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return $this->roomService->read(15);
    }
}
