<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsController extends Controller
{
    public function __construct(
        protected NewsService $newsService
    ){}

    public function store(StoreNewsRequest $request) : JsonResource
    {
        $data = $request->validated();
        $news = $this->newsService->store($data);

        return new NewsResource($news);
    }

    public function index(): JsonResponse
    {
        $news = $this->newsService->showAll();

        return response()->json($news);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);

        $news = $this->newsService->showOne($id, (int) $perPage);

        return response()->json($news);
    }
}
