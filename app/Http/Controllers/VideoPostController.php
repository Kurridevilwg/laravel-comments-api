<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoPostRequest;
use App\Http\Resources\VideoPostResource;
use App\Services\VideoPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoPostController extends Controller
{
    public function __construct(
        protected VideoPostService $videoPostService
    )
    {}

    public function store(StoreVideoPostRequest $request) : JsonResource
    {
        $data = $request->validated();
        $videoPost = $this->videoPostService->store($data);

        return new VideoPostResource($videoPost);
    }

    public function index(): JsonResponse
    {
        $news = $this->videoPostService->showAll();

        return response()->json($news);
    }

    public function show(int $id): JsonResponse
    {
        $news = $this->videoPostService->showOne($id);

        return response()->json($news);
    }
}
