<?php

namespace App\Http\Requests\TimeSlot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Только админ или менеджер отеля может менять доступность
//        return auth()->user()->hasAnyRole(['admin', 'manager']);
        //TODO добавить аутентификацию по ролям
        return true;
    }

    public function rules(): array
    {
        return [
            'is_available' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'is_available.required' => 'Поле is_available обязательно',
            'is_available.boolean'  => 'Поле is_available должно быть true или false',
        ];
    }
}
