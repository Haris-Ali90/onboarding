<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorsCountRequest extends FormRequest
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



    public function messages()
    {
        return [

            'type.required'  => 'Type must be selected',



        ];
    }
    public function rules()
    {
        return [
            'score'        => 'required|integer|max:100',
            'type'   =>     'required'

        ];
    }
}
