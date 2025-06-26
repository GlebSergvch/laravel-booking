<?php

namespace App\Resources\Option;

use App\Resources\AbstractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OptionList_200",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Wi-Fi")
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=1),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=2)
 *     ),
 *     @OA\Property(
 *         property="related",
 *         type="array",
 *         @OA\Items(),
 *         example="[]"
 *     )
 * )
 */
class OptionListResource extends AbstractResource
{
    /**
     * @param Request $request
     * @return array|Arrayable
     */
    public function toArray(Request $request): array|Arrayable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
