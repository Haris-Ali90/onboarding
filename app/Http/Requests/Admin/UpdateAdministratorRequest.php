<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdministratorRequest extends FormRequest
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
            'first_name' => 'required|max:32',
            'last_name'  => 'required|max:32',
            'phone'      => 'required|max:24',
            'email'      => 'required|email|max:128',
			'profile_picture' => 'mimes:jpeg,png,jpg|max:5120',
        ];
    }
}
