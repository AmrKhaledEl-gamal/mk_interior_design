<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('warning', 'يرجى التحقق من الأخطاء: بعض الملفات قد تتجاوز الحجم المسموح به أو غير مدعومة.');
        parent::failedValidation($validator);
    }
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
            'photos.*'  => ['image', 'max:5120'],
            'videos'    => ['nullable', 'array'],
            'videos.*'  => ['mimetypes:video/mp4,video/quicktime', 'max:10240'],
        ];
    }
}
