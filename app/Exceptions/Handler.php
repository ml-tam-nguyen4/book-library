<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception Exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request   Request HTTP
     * @param \Exception               $exception Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $message = "";
        $code = 0;
        if ($request->route()->getPrefix() === 'api') {
            //error 405
            if ($exception instanceof MethodNotAllowedHttpException) {
                $code = Response::HTTP_BAD_METHOD;
                $message = config('define.messages.404_not_found');
            }
            if ($exception instanceof ModelNotFoundException) {
                $code = Response::HTTP_BAD_REQUEST;
                $message = config('define.messages.405_method_failure');
            }
            if ($exception instanceof Exception) {
                $code = Response::HTTP_INTERNAL_ERROR;
                $message = config('define.messages.500_server_error');
            }
            return response()->json([
                'meta' => [
                    'status' => 'failed',
                    'code' => $code,
                    'message' => $message
                ],
            ], $code);
        }
        return parent::render($request, $exception);
    }
}
