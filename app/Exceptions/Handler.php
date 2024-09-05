<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Customize JSON response for model not found
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                [
                    "message" => "Resource not found.",
                ],
                404
            );
        }

        // Customize JSON response for other types of exceptions if needed
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json(
                [
                    "message" => "Validation error.",
                    "errors" => $exception->errors(),
                ],
                422
            );
        }

        // Handle other exceptions
        return parent::render($request, $exception);
    }
}
