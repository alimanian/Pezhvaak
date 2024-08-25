<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('apiResponse', function (
            $data = null,
            bool $success = true,
            string $message = '',
            int $code = 200
        ) {
            $response = [
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ];

            return Response::json($response, $code);
        });
    }
}
