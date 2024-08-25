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
        return response()->apiResponse(PostResource::collection($this->postService->getPaginatedPosts()),
            true, '' , 200);
    }

    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->postService->createPost($request);
            return response()->apiResponse(new PostResource($post), true, 'Post successfully created.' , 201);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage() , $e->getStatusCode());
        }
    }

    public function show(string $id)
    {
        $post = $this->postService->getPostById($id);
        return response()->apiResponse(new PostResource($post), true, '' , 200);
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $post = $this->postService->updatePost($request, $id);
            return response()->apiResponse(new PostResource($post), true, 'Post successfully updated.' , 200);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage() , $e->getStatusCode());
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->postService->deletePost($id);
            return response()->json([], 204);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage() , $e->getStatusCode());
        }
    }
}
