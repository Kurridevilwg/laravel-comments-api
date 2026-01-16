<?php

namespace App\Services;

use App\Models\VideoPost;
use App\Repositories\VideoPostRepository;
use Illuminate\Pagination\CursorPaginator;

class VideoPostService
{
    public function __construct(
        protected VideoPostRepository $repository
    ){}

    public function store(array $request) : VideoPost
    {
        return $this->repository->create($request);
    }

    public function showOne(int $id, int $perPage = 15): VideoPost
    {
        return $this->repository->findWithComments($id, $perPage);
    }

    public function showAll(int $perPage = 15): CursorPaginator
    {
        return $this->repository->paginateWithComments($perPage);
    }
}
