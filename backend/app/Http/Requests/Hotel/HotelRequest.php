<?php

namespace App\Http\Requests\Hotel;

use App\Http\Requests\AbstractApiRequest;
use App\Interfaces\ApiRequestInterface;
use OpenApi\Annotations as OA;

class HotelRequest extends AbstractApiRequest implements ApiRequestInterface
{
    /**
     * @OA\Schema(
     *      schema="Hotel_HotelRequest",
     *      type="object",
     *      required={},
     *      @OA\Property (
     *          property="name",
     *          type="string",
     *          example="Hotel",
     *      ),
     *      @OA\Property (
     *          property="address",
     *          type="string",
     *          example="5 street, 32",
     *      ),
     *      @OA\Property (
     *          property="city",
     *          type="string",
     *          example="York",
     *      ),
     *      @OA\Property (
     *          property="country",
     *          type="string",
     *          example="England",
     *      ),
     * )
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'country' => ['required', 'string'],
        ];
    }
}
