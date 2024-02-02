<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqsRequest extends FormRequest
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

            'description'=>'required',
            'title' => 'required|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/',
            'vendor_id' => 'required',



        ];
    }
}
