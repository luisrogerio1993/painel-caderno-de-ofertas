<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler {

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {
        if($exception instanceof NotFoundHttpException){
        return response()->json(['error' => 'not_found_uri'], 404);
        }else if($exception instanceof MethodNotAllowedHttpException){
        return response()->json(['error' => 'not_found_method'], 405);
        }else if($exception instanceof UnauthorizedHttpException){
        return response()->json(['error' => $exception->getMessage()], 405);
        }else if($exception instanceof Exception){
            //Qualquer outra exception
            //CEP inválido
            if($exception->getCode() === 1001){ //CEP inválido register
                return redirect()->back()
                        ->withInput()
                        ->withErrors(['cep' => ['CEP inválido']]);
            }
        }
        return parent::render($request, $exception);
    }
}