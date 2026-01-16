<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    )
    {}

    public function store(StoreCommentRequest $request) : JsonResource
    {
        $data = $request->validated();

        $comment = $this->commentService->store($data);

        return new CommentResource($comment);
    }

    public function index(Request $request): JsonResponse
    {
        $type    = $request->query('type');
        $id      = $request->query('id');
        $perPage = $request->query('per_page', 15);

        if (!$type || !$id) {
            return response()->json(['error' => 'Требуются параметры type и id'], 400);
        }

        $comments = $this->commentService->getForCommentable($type, (int) $id, (int) $perPage);

        return response()->json(CommentResource::collection($comments));
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $data = $request->validated();

        $updatedComment = $this->commentService->update($comment, $data);

        return response()->json(new CommentResource($updatedComment));
    }

    /**
     * DELETE /api/comments/{comment}
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->delete($comment);

        return response()->json(null, 204);
    }
}
