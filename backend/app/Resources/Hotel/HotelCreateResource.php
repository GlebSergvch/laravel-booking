<?php

namespace App\Resources\Hotel;

use App\Resources\AbstractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class HotelCreateResource extends AbstractResource
{
    /**
     * @OA\Schema (
     *     schema="HotelCreateResponse_200",
     *     type="object",
     *     @OA\Property (
     *         property="success",
     *         type="boolean",
     *     ),
     *     @OA\Property (
     *          property="data",
     *          type="object",
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property (
     *                      property="id",
     *                      type="integer",
     *                  ),
     *                  @OA\Property (
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property (
     *                      property="address",
     *                      type="string",
     *                  ),
     *                  @OA\Property (
     *                      property="city",
     *                      type="string",
     *                  ),
     *                  @OA\Property (
     *                     property="country",
     *                     type="integer",
     *                  ),
     *              )
     *          ),
     *          @OA\Property (
     *               property="related",
     *               type="object",
     *               @OA\Property (
     *                   property="counters",
     *                   type="object",
     *                   @OA\Property (
     *                       property="total",
     *                       type="object",
     *                       @OA\Property(property="label", type="string"),
     *                       @OA\Property(property="value", type="integer"),
     *                       @OA\Property(property="status_id", type="integer")
     *                   ),
     *               ),
     *           ),
     *      ),
     *      @OA\Property (
     *          property="message",
     *          type="string",
     *      ),
     *   )
     * @OA\Schema (
     *      schema="HotelCreateResponse_422",
     *      type="object",
     *      @OA\Property (
     *          property="message",
     *          type="string",
     *      ),
     *      @OA\Property (
     *          property="errors",
     *          type="object",
     *      )
     *  )
     *
     * @param Request $request
     * @return array|Arrayable
     */
    public function toArray(Request $request): array|Arrayable
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'address'    => $this->address,
            'city'       => $this->city,
            'country'    => $this->country,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
