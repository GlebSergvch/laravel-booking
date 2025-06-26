<?php

namespace App\Http\Requests\Option;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Option_OptionRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         example="Wi-Fi",
 *         description="Название опции"
 *     ),
 *     description="Данные для создания или обновления опции"
 * )
 */
class OptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return auth()->user()->hasRole(['admin', 'manager']);
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:options,name,' . ($this->route('id') ?? 'NULL'),
        ];
    }
}
