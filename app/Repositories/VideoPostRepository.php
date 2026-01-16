<?php

namespace App\Repositories;

use App\Models\VideoPost;
use Illuminate\Pagination\CursorPaginator;

class VideoPostRepository
{
    public function create(array $videoPost) : VideoPost
    {
        return VideoPost::create($videoPost);
    }

    public function findWithComments(int $id, int $perPage = 15): VideoPost
    {
        return VideoPost::with([
            'comments' => function ($query) use ($perPage) {
                $query->whereNull('parent_id')
                    ->with(['user:id,name'])
                    ->with(['replies' => function ($q) {
                        $q->with(['user:id,name'])
                            ->latest();
                    }])
                    ->withCount('replies')
                    ->latest('created_at')
                    ->cursorPaginate($perPage);
            }])->findOrFail($id);
    }

    public function paginateWithComments(int $perPage = 15): CursorPaginator
    {
        return VideoPost::with([
            'comments' => function ($query) use ($perPage) {
                $query->whereNull('parent_id')
                    ->with(['user:id,name'])
                    ->with(['replies' => function ($q) {
                        $q->with('user:id,name')
                            ->latest();
                    }])
                    ->withCount('replies')
                    ->latest()
                    ->cursorPaginate($perPage);
            }])
            ->latest()
            ->cursorPaginate($perPage);
    }
}
