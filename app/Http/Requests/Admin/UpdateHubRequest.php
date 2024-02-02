<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHubRequest extends FormRequest
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

            //'profile_picture.image'  => 'Profile image must be an image',
           // 'profile_picture.mimes'  => 'The profile image  must be a file of type: jpeg, png, jpg, gif.',


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
            //'address_city' => 'required',
            //'title'  => 'required|max:50',
            //'email_address'      => 'required|email|unique:dashboard_users,email,NULL,id,deleted_at,NULL',
            //'delivery_process_type' => 'required'
            ];
    }
}
