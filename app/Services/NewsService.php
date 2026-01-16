<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Pagination\CursorPaginator;

class NewsService
{
    public function __construct(
        protected NewsRepository $repository
    ){}

    public function store(array $request) : News
    {
        return $this->repository->create($request);
    }

    public function showOne(int $id, int $perPage = 15): News
    {
        return $this->repository->findWithComments($id, $perPage);
    }

    public function showAll(int $perPage = 15): CursorPaginator
    {
        return $this->repository->paginateWithComments($perPage);
    }
}
