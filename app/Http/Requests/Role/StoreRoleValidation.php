<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleValidation extends FormRequest
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
    public function rules()
    {
        return [
            'name' => ['required','unique:roles','max:255'],
        ];
    }

    public function messages()
    {
        return[
            'name.required'=>'You must fill name',
            'name.max'=>'Name must be maximum of 255 character',
        ];
    }
}
