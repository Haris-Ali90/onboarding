<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubAdminRequest extends FormRequest
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
                'first_name.required' =>'',
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

            'first_name' => 'required|max:50',
            'last_name'  => 'required|max:50',
            'email'      => 'required|email|unique:onboarding_users,email,NULL,id,deleted_at,NULL',
            'phone'      => 'required|max:24|phone:US',
            "rights.*" => "required|string",
            "permissions.*" => "required|string",
            'password'              => 'min:8|required|max:30',
            'confirm_password'      => 'string|min:8|same:password|max:30',
            'upload_file' => 'required|image|mimes:jpeg,png,jpg|max:5120',

        ];
    }
}
