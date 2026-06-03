<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

use Throwable;
use App\Exceptions\{
    NotFoundExcept,
    BadRequestExcept,
    DuplicateExcept,
    ValidationExcept,
    UnauthExcept,
    ForbiddenExcept,
    UnexpectedExcept
};
use App\Helpers\ApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        NotFoundExcept::class,
        BadRequestExcept::class,
        DuplicateExcept::class,
        ValidationExcept::class,
        UnauthExcept::class,
        ForbiddenExcept::class,
        UnexpectedExcept::class
    ];


    public function report(Throwable $e)
    {
        return parent::report($e);
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof ThrottleRequestsException)
        {
            return ApiResponse::ManyRequest();
        }

        if($exception instanceof BindingResolutionException)
        {
            if ($request->is('api/*'))
            {
                return ApiResponse::error(
                    500,
                    'Internal Server Error',
                    'Something went wrong on the server. Please contact support if the issue persists.'
                );
            }

            return parent::render($request, $exception);
        }

        if($request->wantsJson() || str_starts_with($request->path(), 'api/'))
        {
            if ($exception instanceof BadRequestExcept) {
                return ApiResponse::error(400, 'Bad Request', $exception->getMessage());
            }

            if ($exception instanceof DuplicateExcept) {
                return ApiResponse::error(409, 'Duplicated', $exception->getMessage());
            }

            if ($exception instanceof ValidationExcept) {
                return ApiResponse::error(422, 'Unprocessable', $exception->getMessage());
            }

            if ($exception instanceof NotFoundExcept) {
                return ApiResponse::error(404, 'Not Found', $exception->getMessage());
            }

            if ($exception instanceof UnauthExcept) {
                return ApiResponse::error(401, 'Unauthorized', $exception->getMessage());
            }

            if ($exception instanceof ForbiddenExcept) {
                return ApiResponse::error(403, 'Forbidden', $exception->getMessage());
            }

            if ($exception instanceof UnexpectedExcept) {
                return ApiResponse::error(500, 'Internal Server Error', 'Internal Server Error');
            }

            // 5. Laravel/Symfony HTTP exceptions
            if ($exception instanceof HttpException) {
                return match ($exception->getStatusCode()) {
                    500 => ApiResponse::error(500, 'Error', 'Something went wrong'),
                    405 => ApiResponse::error(405, 'Method Not Allowed', 'Method Not Allowed'),
                    403 => ApiResponse::error(403, 'Forbidden', 'Forbidden'),
                    401 => ApiResponse::error(401, 'Unauthorized', 'Unauthorized'),
                    404 => ApiResponse::error(404, 'Not Found', 'Resource not found'),
                    default => ApiResponse::error($exception->getStatusCode(), 'Error', 'Internal Server Error'),
                };
            }

            // 6. Catch-all fallback
            return ApiResponse::error(500, 'Error', 'Internal Server Error');
        }
    }
}
