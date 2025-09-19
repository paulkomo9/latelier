<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'package'  => 'required|max:100',
            'sessions_total'   => 'required',
            'currency'   => 'required',
            'amount'   => 'required',
            'total_amount'   => 'required',
            'validity_quantity'   => 'required|numeric',
            'validity_unit'   => 'required|string',
            'description'        => 'required|string',
            
        ];
    }
}
