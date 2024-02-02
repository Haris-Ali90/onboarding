<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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

            'name'        => 'required|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/|max:50',
            'file_type'        => 'required',
            'document_type'        =>  'required',
            'is_expiry_date'        => 'required',
            'is_file_upload'        => 'required',


        ];
    }
}
