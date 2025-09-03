<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'city.required' => 'Please provide a city name, e.g., "Manila".',
        ];
    }
}
