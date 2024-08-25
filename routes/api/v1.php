<?php

use App\Http\Controllers\Api\V1\FollowerController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserResourceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\PostController;



Route::get('users', [UserController::class, 'index']);
Route::get('users/{user}/posts', [UserResourceController::class, 'userPosts']);
Route::get('users/{user}/comments', [UserResourceController::class, 'userComments']);
Route::get('users/{user}/likes', [UserResourceController::class, 'userLikes']);
Route::get('users/{user}/followers', [FollowerController::class, 'followers']);
Route::get('users/{user}/following', [FollowerController::class, 'following']);
Route::post('users/{user}/follow', [FollowerController::class, 'follow']);
Route::delete('users/{user}/unfollow', [FollowerController::class, 'unfollow']);


Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class)->only(['store', 'destroy']);
Route::apiResource('likes', LikeController::class)->only(['store', 'destroy']);
