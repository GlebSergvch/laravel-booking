<?php

namespace App\Http\Api\V1\Hotel;

use App\DTO\Hotel\HotelDto;
use App\Http\Requests\Hotel\HotelRequest;
use App\Resources\Hotel\HotelCreateResource;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;

class UpdateHotelController
{
    public function __construct(private readonly HotelService $hotelService) {}

    /**
     * @OA\Put(
     *      path="/api/v1/hotel/{id}",
     *      summary="Обновить отель",
     *      security={{"Bearer": {}}},
     *      tags={"Hotel"},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Hotel_HotelRequest")),
     *      @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/HotelCreateResponse_200"))
     * )
     */
    public function __invoke(HotelRequest $request, int $id): JsonResponse
    {
        $data = array_merge(
            ['id' => $id],
            $request->only('name', 'address', 'city', 'country')
        );
        $dto = new HotelDto($data);
        return $this->hotelService->update($dto);
    }
}
