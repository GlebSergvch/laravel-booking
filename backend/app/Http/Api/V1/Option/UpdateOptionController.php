<?php

namespace App\Http\Api\V1\Option;

use App\DTO\Option\OptionDto;
use App\DTO\Room\RoomDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Room\RoomRequest;
use App\Services\OptionService;
use App\Services\RoomService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpdateOptionController extends AbstractController
{
    /**
     * @param OptionService $optionService
     */
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/option/{id}",
     *      summary="Обновление опции",
     *      security={{"Bearer": {}}},
     *      tags={"Option"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID номера",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/Option_OptionRequest")
     *      ),
     *      @OA\Response(
     *            response=200,
     *            description="Success",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="success", type="boolean", example=true),
     *                @OA\Property(
     *                    property="body",
     *                    ref="#/components/schemas/OptionResponse_200"
     *                ),
     *                @OA\Property(property="message", type="string", example="Create successful")
     *            )
     *        ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *          @OA\JsonContent(ref="#/components/schemas/OptionResponse_422")
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
    public function __invoke(int $id, FormRequest $request): JsonResponse
    {
        $dto = new OptionDto(
            name: $request->input('name'),
            id: $id
        );

        return $this->optionService->update($dto);
    }
}
