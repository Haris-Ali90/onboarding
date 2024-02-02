<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderCategoryRequest extends FormRequest
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

            'name'        => 'required|string|max:200|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/',
            'score'        => 'required|integer|max:100',
            'category_type'=> 'required',

        ];
    }
}
