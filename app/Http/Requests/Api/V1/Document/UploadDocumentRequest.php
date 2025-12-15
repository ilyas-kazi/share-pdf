<?php

namespace App\Http\Requests\Api\V1\Document;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload',
            'file.file' => 'The uploaded item must be a file',
            'file.mimes' => 'Only PDF files are allowed',
            'file.max' => 'File size must not exceed 10MB',
            'title.required' => 'Title is required',
            'title.max' => 'Title must not exceed 255 characters',
            'description.max' => 'Description must not exceed 1000 characters',
        ];
    }
}
