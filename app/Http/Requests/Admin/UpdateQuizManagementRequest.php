<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizManagementRequest extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [

            'question.required' => 'Question field is required',
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
            'category_id' => 'required',
            'question' => 'required|string|max:250',
            'questionImage' => 'mimes:jpeg,png,jpg,JPEG,PNG,JPG',
            'answer1' => '',
            'answer2' => '',
            'answer3' => '',
            'answer4' =>' ',


        ];
    }
}
