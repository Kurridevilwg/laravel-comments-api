<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository
{
    public function create(Model $commentable, array $data) : Comment
    {
        return $commentable->comments()->create([
            'user_id'    => $data['user_id'],
            'content'    => $data['content'],
            'parent_id'  => $data['parent_id'] ?? null,
        ]);
    }

    public function update(Comment $comment, array $data): bool
    {
        return $comment->update($data);
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }

    public function getForCommentable(string $type, int $id, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::query()
            ->where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->whereNull('parent_id')
            ->with(['user:id,name'])
            ->with([
                'replies' => function ($q) {
                    $q->with(['user:id,name'])
                        ->latest('created_at');
                }
            ])
            ->withCount('replies')
            ->latest('created_at')
            ->paginate($perPage);
    }
}
