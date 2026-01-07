<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'   => ['required', 'string', 'max:255'],
            'name.ar'   => ['nullable', 'string', 'max:255'],
            'photos'    => ['nullable', 'array'],
            'photos.*'  => ['image'],
            'videos'    => ['nullable', 'array'],
            'videos.*'  => ['mimetypes:video/mp4,video/quicktime', 'max:51200'],
        ];
    }
}
