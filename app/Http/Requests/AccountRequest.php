<?php

namespace App\Http\Requests;

use App\Rules\CheckOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,id,'. $this->user()->id],
            'old_password'  => ['required_with:password', 'string', 'min:8', new CheckOldPassword($this->user())],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'        => [Rule::when(!empty($this->avatar), ['mimes:png,jpg,jpeg', 'max:2048'])]
        ];
    }
}
