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
            'photos.*'  => ['image', 'max:5120'],
            'videos'    => ['nullable', 'array'],
            'videos.*'  => ['mimetypes:video/mp4,video/quicktime', 'max:10240'],
        ];
    }
    public function messages(): array
    {
        return [
            'photos.required' => 'لازم ترفع صورة واحدة على الأقل',
            'photos.*.image'  => 'الملف المرفوع لازم يكون صورة',
            'photos.*.max'    => 'حجم الصورة لازم مايزيدش عن 5 ميجا',

            'videos.required' => 'لازم ترفع فيديو واحد على الأقل',
            'videos.*.file'   => 'الملف المرفوع لازم يكون فيديو',
            'videos.*.max'    => 'حجم الفيديو لازم مايزيدش عن 10 ميجا',
        ];
    }
}
