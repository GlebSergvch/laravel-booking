<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Room_RoomRequest",
 *     type="object",
 *     required={"name", "capacity", "price_per_night"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         example=1,
 *         description="id отеля"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         example="Deluxe Suite",
 *         description="Название номера"
 *     ),
 *     @OA\Property(
 *         property="capacity",
 *         type="integer",
 *         minimum=1,
 *         example=2,
 *         description="Вместимость номера (количество человек)"
 *     ),
 *     @OA\Property(
 *         property="price_per_night",
 *         type="number",
 *         format="float",
 *         minimum=0,
 *         example=150.00,
 *         description="Цена за ночь"
 *     ),
 *     @OA\Property(
 *         property="option_ids",
 *         type="array",
 *         @OA\Items(type="integer", example=1),
 *         example={1, 2},
 *         description="Массив ID опций (например, Wi-Fi, Breakfast), которые связаны с номером"
 *     ),
 *     description="Данные для создания или обновления номера"
 * )
 */
class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
//        return auth()->user()->hasRole(['admin', 'manager']);
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'option_ids' => 'nullable|array',
            'option_ids.*' => 'exists:options,id',
        ];
    }
}
