<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlagCategoryRequest extends FormRequest
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
                'category_name.required' =>  'Main category field is required',
                'child_category_name.*.required' =>  'Second child category field is required',
                'sub_category_name.*.required' =>  'Sub category field is required',
                'parent_cat.*.required' =>  'Kindly select third child parent',
                'sub_category_name.*.max' =>  'Child Category may not be greater than 100 characters.',
                'category_name.max' =>  'Grand Parent Category Name may not be greater than 100 characters.',
                'sub_category_name.*.unique' =>  'The parent category name has already been taken.',
                //'child_category_name.*.unique' =>  'The child category name has already been taken.',
                'child_category_name.*.max' =>  'Parent Category may not be greater than 100 characters.',
                'incident_1_ref_id.*.required*' =>  '1st incident field is required',
                'incident_2_ref_id.*.required*' =>  '2nd incident field is required',
                'incident_3_ref_id.*.required*' =>  '3rd incident field is required',
                'conclusion_ref_id.*.required*' =>  'Conclusion field is required',
                'finance_incident_1.*.required' =>  '1st finance incident field is required',
                'finance_incident_1_operator.*.required' =>  '1st finance incident operator field is required',
                'finance_incident_2.*.required' =>  '2nd finance incident field is required',
                'finance_incident_2_operator.*.required' =>  '2nd finance incident operator field is required',
                'finance_incident_3.*.required' =>  '3rd finance incident field is required',
                'finance_incident_3_operator.*.required' =>  '3rd finance incident operator field is required',
                'finance_conclusion.*.required' =>  'finance conclusion field is required',
                'finance_conclusion_operator.*.required' =>  'finance conclusion operator field is required',
                'rating.*.required' =>  'Rating field is required',
                'rating_operator.*.required' =>  'Rating Operator field is required',
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

            'category_name'                 => 'required|max:100',
            'portal_type'                   => 'required|max:100',
            'child_category_name.*' => 'required|max:100',
            'sub_category_name.*' => 'required|max:100|unique:customer_flag_categories,category_name,NULL,id,deleted_at,NULL',
            'parent_cat.*' => 'required',
            'incident_1_ref_id.*' => 'required',
            "incident_2_ref_id.*"             => "required",
            "incident_3_ref_id.*"           => "required",
            'conclusion_ref_id.*'             => 'required',
            'finance_incident_1.*'            => 'required',
            'finance_incident_1_operator.*'   => 'required',
            'finance_incident_2.*'            => 'required',
            'finance_incident_2_operator.*'   => 'required',
            'finance_incident_3.*'            => 'required',
            'finance_incident_3_operator.*'   => 'required',
            'finance_conclusion.*'            => 'required',
            'finance_conclusion_operator.*'   => 'required',
            'rating.*'                        => 'required|min:0|max:10',
            'rating_operator.*'               => 'required',

        ];
    }
}
