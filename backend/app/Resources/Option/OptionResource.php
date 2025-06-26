<?php

namespace App\Resources\Option;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OptionResponse_200",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Wi-Fi"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-05T23:29:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-05T23:29:00Z"),
 *     @OA\Property(property="created_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="updated_by", type="integer", nullable=true, example=1)
 * )
 * @OA\Schema(
 *     schema="OptionResponse_422",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "name": {"The name field is required."}
 *         }
 *     ),
 *     description="Ответ при ошибке валидации данных"
 * )
 */
class OptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
