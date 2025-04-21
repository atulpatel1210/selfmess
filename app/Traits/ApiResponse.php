<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Common JSON response format
     *
     * @param int $statusCode
     * @param string $message
     * @param mixed|null $data
     * @return JsonResponse
     */
    protected function apiResponse(int $statusCode, string $message, $data = null): JsonResponse
    {
        $response = [
            'state_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Success response
     *
     * @param mixed|null $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return $this->apiResponse($statusCode, $message, $data);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed|null $data
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $statusCode = 400, $data = null): JsonResponse
    {
        $errors = '';
        if (is_object($data)) {
            foreach ($data->messages() as $fieldErrors) {
                if (is_array($fieldErrors)) {
                    $comm = !empty($errors) ? ',' : '';
                    $errors = $errors.$comm.implode(', ', $fieldErrors);
                }
            }
        } elseif (is_array($data)) {
            foreach ($data->messages() as $fieldErrors) {
                if (is_array($fieldErrors)) {
                    $comm = !empty($errors) ? ',' : '';
                    $errors = $errors.$comm.implode(', ', $fieldErrors);
                }
            }
        } else {
            $errors = $message;   
        }

        return $this->apiResponse($statusCode, $errors, null);
    }
}