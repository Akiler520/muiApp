<?php

namespace App\Exceptions;

use App\Lib\MTResponse;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        switch ($exception){
            case ($exception instanceof ModelNotFoundException):
            case ($exception instanceof \HttpException):
            default:
                $message    = $exception->getMessage();
                $code       = $exception->getCode();
                $file       = $exception->getFile();
                $line       = $exception->getLine();

                MTResponse::jsonResponse("[{$line}]" . $message ." -> ". $file, $code);

                break;
        }

        return parent::render($request, $exception);
    }
}
