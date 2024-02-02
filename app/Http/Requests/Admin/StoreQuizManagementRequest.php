<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizManagementRequest extends FormRequest
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
            'type.required' => 'Please select one type',
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

          /*  'type'=>'required',*/
            'order_category_id' => 'required',
       /*     'vendor_id' => 'required',*/
            'question' => 'required|string|max:250',
            'questionImage' => 'mimes:jpeg,png,jpg,JPEG,PNG,JPG',
            'answer1' => '',
            'answer2' => '',
            'answer3' => '',
            'answer4' =>' ',

        ];
    }
}
