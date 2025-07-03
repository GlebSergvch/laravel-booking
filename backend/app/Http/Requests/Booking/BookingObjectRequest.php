<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BookingObject_BookingObjectRequest",
 *     type="object",
 *     required={"room_id", "name", "type"},
 *     @OA\Property(
 *         property="room_id",
 *         type="integer",
 *         example=1,
 *         description="ID номера"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Deluxe Suite",
 *         description="Название объекта бронирования"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="A luxurious suite with a view",
 *         nullable=true,
 *         description="Описание объекта бронирования"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         example="suite",
 *         description="Тип объекта бронирования (например, room, suite, conference_hall)"
 *     ),
 *     description="Данные для создания или обновления объекта бронирования"
 * )
 */
class BookingObjectRequest extends FormRequest
{
    public function authorize(): bool
    {
//        return auth()->user()->hasRole(['admin', 'manager']);
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|integer|exists:rooms,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100', // Можно заменить на enum, если типы фиксированы
        ];
    }
}
