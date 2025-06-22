<?php

namespace PhpHelpers\Helpers;

class ApiResponse
{
    /**
     * Generates a standardized success response.
     *
     * @param mixed $data The data to be returned in the response.
     * @param string|null $message Optional success message.
     * @param int $code HTTP status code for the response (default is 200).
     * @return \Illuminate\Http\JsonResponse The JSON response containing the success data.
     */
    public static function successResponse(
        array|object $data = [],
        string $message = 'Operation successful',
        int $statusCode = 200
    ) {
        $dataResponse = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $statusCode,
        ];

        LogActivityHelper::log([
            'status' => 'success',
            'message' => $message,
            'data_request' => request()?->all(),
            'data_response' => $dataResponse,
        ]);

        return response()->json($dataResponse, $statusCode);
    }

    /**
     * Generates a standardized error response.
     *
     * @param string $message The error message to return in the response.
     * @param int $statusCode The HTTP status code for the error response.
     * @param array|null $errors Optional. Additional error details to include in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the error information.
     */
    public static function errorResponse(
        array|object|string $errors,
        string $message = 'An error occurred',
        int $statusCode = 400,
    ) {
        $dataResponse = [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'code' => $statusCode,
        ];

        LogActivityHelper::log([
            'errors' => $errors,
            'status' => 'error',
            'data_request' => request()?->all(),
            'data_response' => $dataResponse,
        ]);

        return response()->json($dataResponse, $statusCode);
    }
}
