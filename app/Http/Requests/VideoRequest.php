<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class VideoRequest extends FormRequest
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
            'post'  => ['required', 'numeric', Rule::exists('posts', 'id')],
            'video' => ['required', 'string', Rule::exists('posts', 'video')->where('id', $this->input('post'))],
            'tmp'   => []
        ];
    }

    protected function passedValidation()
    {
        $file = $this->file('content');

        if ($file instanceof UploadedFile){
            $tmp = storage_path('tmp') . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $this->video . '.mp4';
            if (!File::exists(dirname($tmp)) ){
                File::makeDirectory(dirname($tmp), recursive: true);
            }
            $fileHandler = fopen($tmp, 'a+');

            fseek($fileHandler, $this->offset);

            fwrite($fileHandler, file_get_contents($file->getRealPath()));

            fclose($fileHandler);

            $this->merge([
                'tmp' => $tmp
            ]);
        }
    }
}
