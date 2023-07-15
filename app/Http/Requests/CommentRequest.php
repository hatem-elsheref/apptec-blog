<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
        if ($this->routeIs('posts.comments.store'))
            return [
                'comment'      => ['required', 'string', 'min:2'],
                'user_id'      => ['required', 'numeric', Rule::exists('users', 'id')],
                'post_id'      => ['required', 'numeric', Rule::exists('posts', 'id')],
            ];
        else
            return [
                'comment'      => ['required', 'string', 'min:2'],
                'is_published' => ['required', 'boolean']
            ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'user_id'      => $this->user()->id,
            'post_id'      => $this->route('post')->id,
        ]);
    }
}
