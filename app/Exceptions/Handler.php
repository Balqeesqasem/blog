<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        $this->renderable(function (Throwable $e, Request $request) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                // Handle 401 Unauthorized response
                return response()->json(
                    [
                        "message" =>
                            "You need to log in to access this resource.",
                    ],
                    401
                );
            }

            // Handle 403 Forbidden response
            if ($e instanceof HttpException && $e->getStatusCode() === 403) {
                return response()->json(
                    [
                        "message" => "Access denied.",
                    ],
                    403
                );
            }

            return parent::render($request, $e);
        });
    }
}
