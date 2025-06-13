<?php
declare(strict_types=1);
namespace App\Services;

use App\Interfaces\ApiPaginationResourceInterface;
use App\Interfaces\ApiResourceInterface;
use App\Interfaces\DtoInterface;
use App\Resources\AbstractResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use function response;

abstract class AbstractApiService
{
    /**
     * @var string
     */
    protected string $resource;

    protected string $direct = 'DESC';

    /**
     * @param $class
     * @return void
     */
    public function setResource($class): void
    {
        $this->resource = $class;
    }

    protected array $errors = [];

    /**
     * @param DtoInterface $dto
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    protected function validate(DtoInterface $dto, array $rules, array $messages): bool
    {
        $validator = Validator::make((array)$dto, $rules, $messages);
        $this->errors = $validator->errors()->messages();
        return $validator->messages()->isEmpty();
    }

    /**
     * @param AnonymousResourceCollection|ApiResourceInterface|array $data
     * @param string $message
     * @return JsonResponse
     */
    public function success(
        AnonymousResourceCollection|ApiResourceInterface|array  $data = [],
        string $message = ''
    ): JsonResponse {
        $response = [
            'success' => true,
            'data'    => $data->collection,
            'message' => $message
        ];

        return response()->json($response);
    }

    /**
     * @param string|null $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function error(?string $message = 'Error', array $errors = [], int $code = 404): JsonResponse
    {
        $response = [
            'message' => $message,
            'errors'  => $errors
        ];

        return response()->json($response, $code);
    }
}
