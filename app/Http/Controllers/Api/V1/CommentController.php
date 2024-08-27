<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentController extends Controller implements HasMiddleware
{
    public function __construct(protected CommentService $commentService) { }

    public static function middleware(): array
    {
        return [new Middleware('auth:sanctum')];
    }

    public function store(StoreCommentRequest $request)
    {
        try {
            $comment = $this->commentService->createComment($request);
            return response()->format(new CommentResource($comment), 'Comment successfully created.', true, 201);
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->commentService->deleteComment($id);
            return response()->format([], 'Comment successfully deleted.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }
}
