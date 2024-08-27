<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends Controller implements HasMiddleware
{
    public function __construct(protected PostService $postService) { }

    public static function middleware(): array
    {
        return [new Middleware('auth:sanctum', except: ['index', 'show'])];
    }

    public function index()
    {
        return response()->format($this->postService->getPaginatedPosts(), 'Posts successfully received.');
    }

    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->postService->createPost($request);
            return response()->format(new PostResource($post), 'Post successfully created.', true , 201);
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage() , false, $e->getStatusCode());
        }
    }

    public function show(string $id)
    {
        $post = $this->postService->getPostById($id);
        return response()->format(new PostResource($post), 'Post successfully retrieved.');
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $post = $this->postService->updatePost($request, $id);
            return response()->format(new PostResource($post), 'Post successfully updated.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->postService->deletePost($id);
            return response()->format([], 'Post successfully deleted.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }
}
