<?php

namespace App\Http\Responses;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class Handler extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * @method static \Illuminate\Http\JsonResponse api(int $code, string $message, ?array $data = null)
         */
        Response::macro('api', function (int $code, string $message, ?array $data = null) {
            $responseObj = [
                'message' => $message,
                'data' => $data,
            ];

            return Response::json($responseObj, $code);
        });
    }
}
