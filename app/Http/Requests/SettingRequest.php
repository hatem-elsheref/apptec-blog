<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $settings = Setting::query()
            ->select('additional', 'key')
            ->where('is_hidden', 0)
            ->pluck('additional', 'key')->toArray();

        $rules = [];

        foreach ($settings as $key => $validation){
            $rules[$key] = json_decode($validation, true)['validation'] ?? [];
        }

        return $rules;
    }
}
