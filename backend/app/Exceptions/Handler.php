<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Helpers\ApiHelper;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): \Illuminate\Http\JsonResponse
    {
        if ($exception instanceof ModelNotFoundException) {
            return ApiHelper::apiError('resource.not_found', [], 404);
        }


        if ($exception instanceof AuthenticationException) {
            return ApiHelper::apiError('auth.unauthenticated', [], 401);
        }

        if ($exception instanceof ValidationException) {
            return ApiHelper::apiError('validation.failed', $exception->errors(), 422);
        }

        if ($exception instanceof HttpException) {
            return ApiHelper::apiError($exception->getMessage() ?: 'HTTP error', [], $exception->getStatusCode());
        }

        return ApiHelper::apiError('Something went wrong.', [
            'exception' => $exception->getMessage()
        ], 500);
    }
}
