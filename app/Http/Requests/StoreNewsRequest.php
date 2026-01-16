<?php

namespace App\Http\Requests;

class StoreNewsRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
