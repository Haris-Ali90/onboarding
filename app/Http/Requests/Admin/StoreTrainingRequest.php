<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingRequest extends FormRequest
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
            'upload_file.mimes'  => 'The upload file must be a file of type: pdf,jpeg, png, jpg, gif, mp4.',
            'upload_file.max' => "Maximum file size to upload is 100MB (102400 KB). If you are uploading a Image/video, try to reduce its resolution to make it under 100MB"
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
         /*   'type'=>'required',*/
            'title' => 'required',
            'order_category_id' => 'required',
            'upload_file'  => 'required|mimes:pdf,doc,docx,jpeg,png,jpg,JPEG,PNG,JPG,mp4,mov|max:102400',
        ];
    }
}
