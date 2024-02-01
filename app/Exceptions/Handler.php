<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (Throwable $e, $request) {
            Log::error($e->getMessage());

            if ($e instanceof QueryException) {
                if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'users.users_email_unique')){
                    $genericErrorMessage = "Email is already in use.";
                } else {
                    $genericErrorMessage = "A database error occurred.";
                }
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => $genericErrorMessage], 500);
            } else {
                $errorMessage = urlencode($genericErrorMessage);
                return redirect()->away(env('FRONT_END_URL') . '?error=' . $errorMessage);
            }
        });
    }
}
