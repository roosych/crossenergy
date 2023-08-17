<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|min:2|max:100',
            'number' => 'nullable|string|min:1|max:10',
            'phone' => 'required|string|min:10|max:20|unique:drivers,phone,' .$this->driver->id,
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
            'owner_id' => 'nullable|integer|exists:owners,id',
            'capacity' => 'nullable|string|min:2|max:100',
            'citizenship' => 'required|string|min:2|max:100',
            'dimension' => 'nullable|string|min:2|max:100',
            'zipcode' => 'nullable|string',
            'location' => 'nullable|string|max:150',
            'latitude' => 'nullable|between:-90,90',
            'longitude' => 'nullable|between:-180,180',

            'future_zipcode' => 'nullable|integer',
            'future_location' => 'nullable|string|max:150',
            'future_latitude' => 'nullable|between:-90,90',
            'future_longitude' => 'nullable|between:-180,180',

            'future_datetime' => 'nullable|date',

            'note' => 'nullable|string|max:500',
        ];
    }
}
