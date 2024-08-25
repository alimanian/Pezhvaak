<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getPaginatedUsers()
    {
        return User::withCount(['posts', 'likes', 'comments'])
            ->paginate(10);
    }
}
