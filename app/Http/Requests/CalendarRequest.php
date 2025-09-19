<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarRequest extends FormRequest
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
            'title'              => 'required|max:100',
            'starts_date'        => 'required|date',
            'ends_date'          => 'required|date',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i',
            'location_latitude'  => 'required|numeric',
            'location_longitude' => 'required|numeric',
            'slots'              => 'required|integer|min:1',
            'slots_remaining'    => 'required|integer',
            'trainer_id'         => 'required|exists:users,id', // Optional: add DB check
            'description'        => 'required|string',
        ];
    }
}
