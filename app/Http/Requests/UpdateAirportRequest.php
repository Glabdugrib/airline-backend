<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAirportRequest extends FormRequest
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
          'code' => 'sometimes|string|size:3|unique:airports,code,' . $this->route('airport')->id,
          'name' => 'sometimes|string|max:50',
          'city' => 'sometimes|string|max:50',
          'country' => 'sometimes|string|max:50',
        ];
    }
}
