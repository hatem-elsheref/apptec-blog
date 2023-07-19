<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
            ->select('additional', 'id')
            ->where('is_hidden', 0)
            ->pluck('additional', 'id')->toArray();

        $rules = [];

        foreach ($settings as $key => $validation){
            $rules["setting_$key"] = json_decode($validation, true)['validation'] ?? [];
        }


        return $rules;
    }

    protected function prepareForValidation()
    {
        foreach ($this->rules() as $name => $rule){
            if (!$this->filled($name) && Str::contains($rule, 'in:'))
                $this->merge([
                    $name => $this->input($name, 0)
                ]);

        }
    }
}
