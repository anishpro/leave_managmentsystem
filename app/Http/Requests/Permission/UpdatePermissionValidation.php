<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionValidation extends FormRequest
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
            'name' => ['required','unique:permissions,name,'.request()->get('id'),'max:255','id_validation'],
            'guard_name' => ['required','max:255'],
        ];
    }

    public function messages()
    {
        $this->customValidation();
        return[
            'name.required'=>'You must fill name',
            'guard_name.required'=>'You must fill name',
            'name.max'=>'Name must be maximum of 255 character',
            'guard_name.max'=>'Name must be maximum of 255 character',
            'name.id_validation'=>'Invalid ID Passed'
        ];
    }

    public function customValidation()
    {
        Validator::extend('id_validation', function ($attribute, $value, $parameters, $validator) {
            $id =request()->get('id');

            if (Permission::find($id)) {
                return true;
            }

            return false;
        });
    }
}
