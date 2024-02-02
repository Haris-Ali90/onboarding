<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJoeyDocumentRequest extends FormRequest
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

            'driver_permit_date' => 'date_format:d/m/Y',
            'driver_license_date'  => 'date_format:d/m/Y',
            'study_permit_date'      => 'date_format:d/m/Y',
            'vehicle_insurance_date'      => 'date_format:d/m/Y',
            'additional_document_1_date' => 'date_format:d/m/Y',
            'additional_document_2_date'  => 'date_format:d/m/Y',
            'additional_document_3_date'      => 'date_format:d/m/Y',
            'sin_date'      => 'date_format:d/m/Y',


            //'confirm_password'      => 'string|min:6|same:password|max:30',
            //'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif',

        ];
    }
}
