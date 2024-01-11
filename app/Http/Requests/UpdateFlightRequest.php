<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departure_airport_id' => 'sometimes|exists:airports,id',
            'arrival_airport_id' => 'sometimes|exists:airports,id',
            'departure_at' => 'sometimes|date_format:Y-m-d H:i',
            'arrival_at' => 'sometimes|date_format:Y-m-d H:i|after_or_equal:departure_at',
            'price' => 'sometimes|numeric|min:0',
            'stopovers' => 'sometimes|integer|min:0|max:2',
        ];
    }
}
