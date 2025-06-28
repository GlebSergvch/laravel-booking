<?php

namespace App\Http\Api\V1\Hotel;

use App\DTO\Hotel\HotelDto;
use App\Http\Api\V1\AbstractController;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexHotelController extends AbstractController
{
    public function __construct(private readonly HotelService $hotelService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/hotel",
     *      summary="Список отелей с пагинацией",
     *      security={{"Bearer": {}}},
     *      tags={"Hotel"},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Номер страницы",
     *          required=false,
     *          @OA\Schema(type="integer", default=1)
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Количество элементов на странице",
     *          required=false,
     *          @OA\Schema(type="integer", default=15)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="body",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/HotelList_200")
     *              ),
     *              @OA\Property(property="message", type="string", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
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
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        return $this->hotelService->read((int) $perPage);
    }
}
