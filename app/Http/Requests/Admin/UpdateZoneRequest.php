<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateZoneRequest extends FormRequest
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

    public function messages()
    {
        return [
            'latitude.required' =>'please enter valid Address',
            'phone.required' => 'Please enter the correct phone-number',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name'        => 'required|string|max:50',
            'latitude'        => 'required',
            'longitude'        =>  'required',
            'radius'        => 'required',
            'time_zone'        => 'required|string|max:100',



        ];
    }
}
