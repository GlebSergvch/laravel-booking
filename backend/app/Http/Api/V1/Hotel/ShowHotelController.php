<?php

namespace App\Http\Api\V1\Hotel;

use App\Resources\Hotel\HotelCreateResource;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;

class ShowHotelController
{
    public function __construct(private readonly HotelService $hotelService) {}

    /**
     * @OA\Get(
     *      path="/api/v1/hotel/{id}",
     *      summary="Получить один отель",
     *      security={{"Bearer": {}}},
     *      tags={"Hotel"},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/HotelCreateResponse_200"))
     * )
     */
    public function __invoke(int $id): JsonResponse
    {
        return $this->hotelService->find($id);
    }
}
