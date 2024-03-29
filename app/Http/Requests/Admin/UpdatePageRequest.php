<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            // 'media_file_id'     => 'required',
            'page_title'        => 'required|max:190',
            'content'           => 'max:65535',
          //  'meta_title'        => 'max:190',
           // 'meta_keywords'     => 'max:65535',
           // 'meta_description'  => 'max:65535'
        ];
    }
}
