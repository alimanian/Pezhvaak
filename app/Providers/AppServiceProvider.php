<?php

namespace App\Providers;

use App\Services\ApiResponseFormater;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('format', function ($data = null, string $message = '', bool $success = true, int $code = 200) {
            return Response::json(ApiResponseFormater::format($data, $message, $success), $code);
        });
    }
}
