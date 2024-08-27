<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->format($this->userService->getPaginatedUsers(), 'User retrieved successfully.');
    }
}
