<?php

namespace PhpHelpers\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpHelpers\Helpers\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!($response instanceof JsonResponse)) {
            return $response;
        }

        if (!$response->isSuccessful()) {
            return $response;
        }

        $data = $response->getData(true);

        return ApiResponse::successResponse($data, 'Operation successful', $response->getStatusCode());
    }
}
