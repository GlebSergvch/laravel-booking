<?php

namespace App\Http\Api\V1\Option;

use App\DTO\Option\OptionDto;
use App\Http\Api\V1\AbstractController;
use App\Http\Requests\Option\OptionRequest;
use App\Services\OptionService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CreateOptionController extends AbstractController
{
    public function __construct(private readonly OptionService $optionService) {}

    /**
     * @OA\Post(
     *      path="/api/v1/option",
     *      summary="Создание опции",
     *      security={{"Bearer": {}}},
     *      tags={"Option"},
     *      @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/Option_OptionRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Опция успешно создана",
     *          @OA\JsonContent(ref="#/components/schemas/OptionResponse_200")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(ref="#/components/schemas/OptionResponse_422")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Неавторизованный доступ",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function __invoke(OptionRequest $request): JsonResponse
    {
        $dto = new OptionDto(
            name: $request->input('name')
        );

        return $this->optionService->create($dto);
    }
}
