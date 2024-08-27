<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\StoreLikeRequest;
use App\Http\Resources\LikeResource;
use App\Services\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LikeController extends Controller implements HasMiddleware
{
    public function __construct(protected LikeService $likeService) { }

    public static function middleware(): array
    {
        return [new Middleware('auth:sanctum')];
    }

    public function store(StoreLikeRequest $request)
    {
        try {
            $like = $this->likeService->createLike($request);
            return response()->format(new LikeResource($like), 'Like successfully created.', true, 201);
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }

    public function destroy(string $likeId): JsonResponse
    {
        try {
            $this->likeService->deleteLike($likeId);
            return response()->format([], 'Like successfully deleted.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }
}
