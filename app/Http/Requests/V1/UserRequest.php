<?php

namespace App\Http\Requests\V1;

use App\Rules\EmailDomainValidation;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:50|alpha',
            'last_name' => 'required|string|max:50|alpha',
            'email' => ['required', 'string', 'email', 'unique:users', new EmailDomainValidation],
            'password' => 'required',
        ];
    }
}
