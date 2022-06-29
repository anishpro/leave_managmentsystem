<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class ContractUpdateRequest extends FormRequest
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
                'contract_start' => 'required|date',
                'contract_end' =>'required|date',
                'contract_type_id' => 'required',
                'is_active' => 'required',
                'user_id' => 'required'
            ];
    }
}
