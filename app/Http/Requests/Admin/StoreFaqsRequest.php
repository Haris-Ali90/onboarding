<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqsRequest extends FormRequest
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
            'upload_file.required'  => 'Upload file is required',
            'upload_file.mimes'  => 'The upload file must be a file of type: jpeg, png, jpg, gif, mp4.',
            'upload_file.max' => "Maximum file size to upload is 5MB (5120 KB). If you are uploading a Image/video, try to reduce its resolution to make it under 5MB"
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
            'description'=>'required',
            'title' => 'required|regex:/^[a-zA-Z\s][a-zA-Z0-9\s]+$/',
            'vendor_id' => 'required',

        ];
    }
}
