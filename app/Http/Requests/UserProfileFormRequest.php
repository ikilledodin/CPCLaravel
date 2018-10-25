<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileFormRequest extends FormRequest
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
            //
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'weight' => 'numeric',
            'height' => 'numeric',
            'city' => 'min:2',
            'country' =>'min:2',
            'gender' => 'integer',
            'avatar'=>  'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'birthdate' =>'date_format:Y-m-d'
        ];
    }
}
