<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'title'  => ['required', 'string', 'min:2'],
            'body'   => ['required', 'string', 'min:2'],
            'image'  => [Rule::when($this->route()->getName() === 'posts.store', ['file', 'mimes:png,jpg,jpeg', 'max:2048'])],
            'user_id'=> ['required', 'numeric', Rule::exists('users', 'id')]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
           'user_id' => $this->user()->id
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        $this->expectsJson()
            ? throw new HttpResponseException(response()->json([
                'message' => 'validation Error',
                'errors'  => $validator->errors(),
                'status'  => false,
                'code'    => 422
        ], 422))
            : parent::failedValidation($validator);
    }
}
