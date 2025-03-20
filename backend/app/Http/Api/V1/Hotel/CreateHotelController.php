<?php

namespace App\Http\Api\V1\Hotel;

use App\DTO\Hotel\HotelDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Hotel\HotelRequest;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CreateHotelController extends AbstractController
{
    /**
     * @param HotelService $hotelService
     */
    public function __construct(
        private readonly HotelService $hotelService,
    ) {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/hotel",
     *      summary="Создание отеля",
     *      security={{"Bearer": {}}},
     *      tags={"Hotel"},
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/Hotel_HotelRequest")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(ref="#/components/schemas/HotelCreateResponse_200")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/HotelCreateResponse_422")
     *     ),
     * )
     *
     * @param HotelRequest $request
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function __invoke(HotelRequest $request): JsonResponse
    {
        $dto = new HotelDto(...$request->only(
            'name',
            'address',
            'city',
            'country',
        ));

        return $this->hotelService->create($dto);
    }
}
