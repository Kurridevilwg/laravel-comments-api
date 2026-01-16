<?php

namespace App\Http\Requests;

class StoreCommentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'user_id'          => ['required', 'exists:users,id'],
            'content'          => ['required', 'string', 'min:1', 'max:5000'],
            'parent_id'        => ['nullable', 'exists:comments,id'],

            'commentable_type' => [
                'required_without:parent_id',
                'prohibited_if:parent_id,*',
                'string',
                'in:news,videoPost',
            ],

            'commentable_id'   => [
                'required_without:parent_id',
                'prohibited_if:parent_id,*',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required'       => 'Текст комментария обязателен',
            'content.min'            => 'Комментарий не может быть пустым',
            'commentable_type.in'    => 'Недопустимый тип сущности для комментария',
            'parent_id.exists'       => 'Родительский комментарий не найден',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
