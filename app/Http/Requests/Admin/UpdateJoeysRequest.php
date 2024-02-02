<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJoeysRequest extends FormRequest
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
            'first_name' => 'required|max:50',
            'last_name'  => 'required|max:50',
            'phone'  => 'required|max:24',
            'ComdataCard'  => 'max:16',
            'HSTNumber'  => 'max:15',
            'address'  => 'required',
            //'suite'  => 'required',
            'postalCode'  => 'required',
            //'vehicle_id'  => 'required',
            'upload_file' => 'mimes:jpeg,png,jpg|max:5120',

        ];
    }
}
