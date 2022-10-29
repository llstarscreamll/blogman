<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class EditUserRequest extends FormRequest
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
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'type' => ['nullable','in:'.implode(',', [User::BLOGGER_TYPE, User::SUPERVISOR_TYPE, User::ADMIN_TYPE])],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', Rule::unique('users')->ignore($this->user)],
            'password' => ['nullable','string','min:6','confirmed'],
        ];
    }
}
