<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:100|string',
            'description' => 'max:255|string',
            'location' => 'max:100|string',
            'date' => 'date_format:Y-m-d|string',
            'time' => 'date_format:H:i:s|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.date_format' => 'The :attribute provided is invalid.',
            'time.date_format' => 'The :attribute provided is invalid.',
        ];
    }
}
