<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;
use Throwable;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
  /**
   * The list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

  public function render($request, Throwable $exception)
  {
    // if ($request->is('api/*') && !Auth::guard('merchant_api')->check()) {
    //   return response()->json(['error' => 'Unauthorized.'], 401);
    // }

    return parent::render($request, $exception);
  }

  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      //
    });
  }
}
