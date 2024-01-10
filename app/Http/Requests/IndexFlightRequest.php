<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexFlightRequest extends FormRequest
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
          'departure_at_date' => 'sometimes|date_format:Y-m-d',
          'departure_at_from' => [
            'sometimes',
            'date_format:H',
            Rule::requiredIf(function () {
                return $this->filled(['departure_at_to', 'departure_at_date']);
            }),
          ],
          'departure_at_to' => [
              'sometimes',
              'date_format:H',
              'after:departure_at_from',
              Rule::requiredIf(function () {
                  return $this->filled(['departure_at_from', 'departure_at_date']);
              }),
          ],
          'arrival_at_date' => 'sometimes|date_format:Y-m-d|after:departure_at_date',
          'arrival_at_from' => [
            'sometimes',
            'date_format:H',
            Rule::requiredIf(function () {
                return $this->filled(['arrival_at_to', 'arrival_at_date']);
            }),
          ],
          'arrival_at_to' => [
              'sometimes',
              'date_format:H',
              'after:arrival_at_from',
              Rule::requiredIf(function () {
                  return $this->filled(['arrival_at_from', 'arrival_at_date']);
              }),
          ],
          'price_from' => 'sometimes|numeric|min:0',
          'price_to' => 'nullable|numeric' . ($this->filled('price_from') ? '|gte:price_from' : ''),
          'stopovers_from' => 'sometimes|integer|min:0',
          'stopovers_to' => 'sometimes|integer|gte:stopovers_from|max:2',
          'sort_by' => [
              'sometimes',
              'string',
              'regex:/^((\+|\-)?\w+(,)?)*$/'
          ],
          'page' => 'sometimes|integer|min:1',
          'limit' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
