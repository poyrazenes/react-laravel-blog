<?php

namespace App\Exceptions;


use App\Support\Response\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            $response = new Response();

            if ($exception instanceof NotFoundHttpException) {
                $response->setCode(404)->setMessage('Sonuç bulunamadı!');
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $response->setCode(405)->setMessage('İstek metodu yanlış!');
            } elseif ($exception instanceof AuthenticationException) {
                $response->setCode(401)->setMessage('Yetkisiz giriş!');
            } elseif ($exception instanceof TooManyRequestsHttpException) {
                $response->setCode(429)->setMessage('Çok fazla istek gönderildi!');
            } elseif ($exception instanceof ModelNotFoundException) {
                $response->setCode(404)->setMessage('Sonuç bulunamadı!');
            } else {
                $response->setCode(500)->setMessage(
                    get_class($exception) . ': ' . $exception->getMessage()
                );
            }

            return $response->respond();
        }

        return parent::render($request, $exception);
    }
}
