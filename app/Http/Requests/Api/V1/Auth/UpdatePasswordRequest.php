<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'required_with:new_password',
            'new_password' => 'nullable|confirmed',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('old_password') && !Hash::check($this->old_password, \Auth::user()->password)) {
                $validator->errors()->add('old_password', 'Old password not valid');
            }
        });
    }
}
