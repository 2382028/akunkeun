<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
        protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guard = $exception->guards()[0];

        switch ($guard) {
            case 'administrator':
                $login = route('administrator.login'); // bikin route khusus admin
                break;
            case 'penyedia':
                $login = route('penyedia.login'); // bikin route khusus penyedia
                break;
            case 'akun_penyewa':
                $login = route('penyewa.login'); // bikin route khusus penyewa
                break;
            default:
                $login = route('login'); // fallback ke default /akses
                break;
        }

        return redirect()->guest($login);
    }
}
