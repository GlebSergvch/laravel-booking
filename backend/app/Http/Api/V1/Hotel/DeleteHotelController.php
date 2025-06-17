<?php

namespace App\Http\Api\V1\Hotel;

use App\DTO\Hotel\HotelDto;
use App\Http\Requests\Hotel\HotelRequest;
use App\Resources\Hotel\HotelCreateResource;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;

class DeleteHotelController
{
    public function __construct(private readonly HotelService $hotelService) {}

    /**
     * @OA\Delete(
     *      path="/api/v1/hotel/{id}",
     *      summary="Удалить отель",
     *      security={{"Bearer": {}}},
     *      tags={"Hotel"},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=204, description="No Content")
     * )rooms


     */
    public function __invoke(int $id): JsonResponse
    {
        $this->hotelService->delete($id);
        return response()->json([], 204);
    }
}
