<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['sometimes', 'required', 'string', 'min:1', 'max:5000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
