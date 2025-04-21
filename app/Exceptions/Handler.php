<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;

class Handler extends ExceptionHandler
{
    /**
     * Format all responses in a standard JSON structure.
     */
    protected function formatResponse($message, $status, $data = null)
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    /**
     * Handle unauthenticated response with a standardized format.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->formatResponse('Unauthorized', 401, null);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return $this->formatResponse('Unauthenticated.', 401);
        }
        if ($exception instanceof ModelNotFoundException) {
            return $this->formatResponse('Record not found.', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->formatResponse('Resource not found.', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->formatResponse('Method not allowed.', 405);
        }

        if ($exception instanceof ValidationException) {
            return $this->formatResponse('Validation error.', 422, $exception->errors());
        }

        // Handle UniqueConstraintViolationException
        if ($exception instanceof UniqueConstraintViolationException) {
            return $this->formatResponse('Duplicate entry. The email already exists.', 422);
        }

        if ($exception instanceof QueryException) {
            // if ($e->getCode() === '23000') {
            //     return $this->errorResponse('Duplicate entry. Email or mobile already exists.', 422);
            // }
            return $this->errorResponse('Database error: ' . $exception->errors(), 500);

            // return $this->formatResponse('Duplicate entry. The email already exists.', 422);
        }

        // Handle all other unexpected exceptions
        return $this->formatResponse('Internal Server Error', 500);
    }
}