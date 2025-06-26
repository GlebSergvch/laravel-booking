<?php

namespace App\Http\Api\V1\Option;

use App\Http\Api\V1\AbstractController;
use App\Services\OptionService;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DeleteOptionController extends AbstractController
{
    /**
     * @param OptionService $optionService
     */
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/option/{id}",
     *      summary="Удаление опции",
     *      security={{"Bearer": {}}},
     *      tags={"Option"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID опции",
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
        return $this->optionService->delete($id);
    }
}
