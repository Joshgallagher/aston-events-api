<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'category_id' => 'required|numeric',
            'related_event_id' => 'nullable|exists:events,id|numeric',
            'name' => 'required|max:100|string',
            'description' => 'required|max:2500|string',
            'location' => 'required|max:100|string',
            'date' => 'required|date_format:Y-m-d|string',
            'time' => 'required|date_format:H:i:s|string',
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
            'related_event_id.exists' => 'The selected event does not exist.',
            'date.date_format' => 'The :attribute provided is invalid.',
            'time.date_format' => 'The :attribute provided is invalid.',
        ];
    }
}
