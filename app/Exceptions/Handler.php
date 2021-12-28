<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')){

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'applicationCode' => '404',
                    'message' => 'La risorsa richiesta non esiste',
                    'exceptionMessage' => $e->getMessage()
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'applicationCode' => '404',
                    'message' => 'La risorsa non esiste',
                ], 404);
            }

            if ($e instanceof BadRequestException || $e instanceof ValidationException) {
                return response()->json([
                    'applicationCode' => '400',
                    'message' => 'Parametri di richiesta non validi',
                    'exceptionMessage' => $e->getMessage()
                ], 400);
            }

            if ($e instanceof MissingScopeException ) {
                return response()->json([
                    'applicationCode' => '403',
                    'message' => 'Permessi non validi!',
                    'exceptionMessage' => $e->getMessage()
                ], 403);
            }

            if ($e instanceof AuthorizationException ) {
                return response()->json([
                    'applicationCode' => '403',
                    'message' => 'Errore di autorizzazione',
                    'exceptionMessage' => $e->getMessage()
                ], 403);
            }
            
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'applicationCode' => '401',
                    'message' => 'Autenticazione non valida, controllare username e password',
                    'exceptionMessage' => $e->getMessage()
                ], 401);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'applicationCode' => '404',
                    'message' => 'Risorsa non allocata',
                    'exceptionMessage' => $e->getMessage()
                ], 404);
            }

            if ($e instanceof QueryException) {
                return response()->json([
                    'applicationCode' => '409',
                    'message' => 'Entita duplicata',
                    'exceptionMessage' => $e->getMessage()
                ], 409);
            }

            return response()->json([
                'applicationCode' => '500',
                'message' => 'Errore generico',
                'exceptionMessage' => $e->getMessage()
            ], 500);
            
        };

    }
}
