<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Services\UserResourceService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserResourceController extends Controller
{
    public function __construct(protected UserResourceService $userResourceService) { }

    public function userPosts(string $userId)
    {
        try {
            $posts = $this->userResourceService->getUserPosts($userId);
            return response()->apiResponse(PostResource::collection($posts), true, '', 200);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage(), $e->getStatusCode());
        }
    }

    public function userLikes(string $userId)
    {
        try {
            $likes = $this->userResourceService->getUserLikes($userId);
            return response()->apiResponse(LikeResource::collection($likes), true, '', 200);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage(), $e->getStatusCode());
        }
    }

    public function userComments(string $userId)
    {
        try {
            $comments = $this->userResourceService->getUserComments($userId);
            return response()->apiResponse(CommentResource::collection($comments), true, '', 200);
        } catch (HttpException $e) {
            return response()->apiResponse([], false, $e->getMessage(), $e->getStatusCode());
        }
    }
}
