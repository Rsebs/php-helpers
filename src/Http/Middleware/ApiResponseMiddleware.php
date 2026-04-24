<?php

namespace PhpHelpers\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
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

        $original = $response->original;

        // Paginate
        if ($original instanceof LengthAwarePaginator || $original instanceof Paginator) {
            $meta = [
                'current_page' => $original->currentPage(),
                'per_page' => $original->perPage(),
                'total' => method_exists($original, 'total') ? $original->total() : null,
                'last_page' => method_exists($original, 'lastPage') ? $original->lastPage() : null,
                'next_page' => $original->nextPageUrl(),
                'prev_page' => $original->previousPageUrl(),
            ];
            return ApiResponse::successResponse($original->items(), 'Operation successful', $response->getStatusCode(), $meta);
        }

        // Normal
        return ApiResponse::successResponse($original, 'Operation successful', $response->getStatusCode());
    }
}
