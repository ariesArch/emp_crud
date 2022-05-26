<?php

namespace App\Http\Requests\Api\V1\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'first_name'      => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'company_id'  => 'required|integer|exists:employees,id',
            'department_id'  => 'required|integer|exists:departments,id',
            'role_id'  => 'required|integer|exists:roles,id',
            'email'     => 'nullable|email|unique:employees,email',
            'phone'  => 'nullable|numeric|unique:employees,phone',
            'address'   => 'nullable|string',
        ];
    }
}
