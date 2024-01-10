<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightRequest extends FormRequest
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
          'departure_airport_id' => 'required|exists:airports,id',
          'arrival_airport_id' => 'required|exists:airports,id',
          'departure_at' => 'required|date_format:Y-m-d H:i',
          'arrival_at' => 'required|date_format:Y-m-d H:i|after_or_equal:departure_at',
          'price' => 'required|numeric|gt:0',
          'stopovers' => 'required|integer|min:0|max:2',
        ];
    }
}
