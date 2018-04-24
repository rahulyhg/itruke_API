<?php

namespace App\Exceptions;

use App\Helper\Log;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ErrorException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use UnexpectedValueException;

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
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        //抓取异常,存储到log
        if ($e instanceof NotFoundHttpException) {
            $message = '404 NotFound  路由没找到';
        } else if ($e instanceof FatalErrorException || $e instanceof ErrorException || $e instanceof UnexpectedValueException) {
            $message = $e->getMessage(). ' In '.$e->getFile().':'.$e->getLine();
        } else if ($e instanceof MethodNotAllowedHttpException) {
            $message = '不能使用该请求方式请求该接口';
        } else {
            $message = $e;
        }
        Log::error($message.' ['.$e->getCode().']');

        //系统原代码
        return parent::render($request, $e);
    }
}
