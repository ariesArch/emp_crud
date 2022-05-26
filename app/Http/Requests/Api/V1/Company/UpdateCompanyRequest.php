<?php

namespace App\Http\Requests\Api\V1\Company;

use App\Http\Requests\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name'      => 'required|string|max:255|unique:companies,name,' . $this->route('company')->id,
            'email'     => 'nullable|email|unique:companies,email,' . $this->route('company')->id,
            'address'   => 'nullable|string',
        ];
    }
}
