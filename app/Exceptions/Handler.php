<?php

namespace App\Exceptions;

use App;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
  * Report or log an exception.
  *
  * @param  \Throwable  $exception
  * @return void
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
  * @return \Illuminate\Http\Response
  */
 public function render($request, Throwable $exception)
 {

  if ($request->is('api/*') || $request->wantsJson()) {

   // this part is from render function in Illuminate\Foundation\Exceptions\Handler.php
   // works well for json
   $exception = $this->prepareException($exception);

   if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
    return $exception->getResponse();
   } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
    return $this->unauthenticated($request, $exception);
   } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
    return $this->convertValidationExceptionToResponse($exception, $request);
   }

   // we prepare custom response for other situation such as modelnotfound
   $response           = [];
   $response['errors'] = $exception->getMessage();

   if (config('app.debug')) {
    // $response['trace'] = $exception->getTrace();
    $response['code'] = $exception->getCode();
   }

   // we look for assigned status code if there isn't we assign 500
   $statusCode = method_exists($exception, 'getStatusCode')
   ? $exception->getStatusCode()
   : 500;

   return response()->json($response, $statusCode);
  }

  return parent::render($request, $exception);

//   return parent::render($request, $exception);

 }

 /**
  * Render an exception into an HTTP response.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Throwable  $exception
  * @return array|boolean $response
  */
 public function apiResponse($request, Throwable $exception)
 {

  // Method not allowed.
  if (get_class($exception) === "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException") {
   $method = $request->method();
   return [
    'status'  => 405,
    'message' => "$method method is not allowed for the requested route.",
   ];
  }

  // Resource not found.
  if (get_class($exception) === "Illuminate\Database\Eloquent\ModelNotFoundException") {
   return [
    'status'  => 404,
    'message' => 'Resource not found.',
   ];
  }

  // If it's not one of these known exceptions, return false.
  return false;
 }

}
