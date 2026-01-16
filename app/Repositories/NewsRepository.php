<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository
{
    public function create(array $news) : News
    {
        return News::create($news);
    }

    public function findWithComments(int $id, int $perPage = 15): News
    {
        return News::with([
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
        return News::with([
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
