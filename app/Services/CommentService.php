<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public function __construct(
        private readonly CommentRepository $repository
    ) {
    }

    public function store(array $data) : Comment
    {
        $commentData = [
            'user_id' => $data['user_id'],
            'content' => $data['content'],
        ];

        if (!empty($data['parent_id'])) {
            $parent = Comment::findOrFail($data['parent_id']);
            $commentData['commentable_type'] = $parent->commentable_type;
            $commentData['commentable_id']   = $parent->commentable_id;
            $comment = $parent->replies()->create($commentData);

            return $comment->load('user:id,name');
        }

        $typeMap = [
            'news'      => \App\Models\News::class,
            'videoPost' => \App\Models\VideoPost::class,
        ];

        $type = $data['commentable_type'] ?? null;

        if (!$type || !isset($typeMap[$type])) {
            throw new \InvalidArgumentException(
                "Необходимо указать commentable_type (news, videoPost)"
            );
        }

        $commentable = $typeMap[$type]::findOrFail($data['commentable_id'] ?? 0);
        $comment = $commentable->comments()->create($commentData);

        return $comment->load('user:id,name');
    }

    public function getForCommentable(string $type, int $id, int $perPage = 15) : LengthAwarePaginator
    {
        $typeMap = [
            'news'      => \App\Models\News::class,
            'videoPost' => \App\Models\VideoPost::class,
        ];

        if (!isset($typeMap[$type])) {
            throw new \InvalidArgumentException("Недопустимый тип: {$type}");
        } else {
            $type = $typeMap[$type];
        }

        //$typeMap[$type]::findOrFail($id);

        return $this->repository->getForCommentable($type, $id, $perPage);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $this->repository->update($comment, $data);

        return $comment->refresh()->load('user:id,name');
    }

    public function delete(Comment $comment): void
    {
        $this->repository->delete($comment);
    }
}
