<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Base\Http\Controllers\Api\BaseController;
use Modules\Subscriptions\Exceptions\ModulePermissionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\Access\AuthorizationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {   
        // For ajax request or api request only
        if($request->ajax() || $request->wantsJson()) {
            if($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) 
            {
                return (new BaseController)->errorNotFound();
            }

            if($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return (new BaseController)->errorNotFound();
            }
            
            if($e instanceof AuthorizationException) {
                return (new BaseController)->errorForbidden();
            }

            if($e instanceof ModulePermissionException) {
                return (new BaseController)->errorForbidden('Please subscribe to get enough access to this action, <a href="'.route('user.subscriptions.pricings.index').'">subscribe now</a>.');
            }
        } else {
            if ($e instanceof ModulePermissionException) {
                return redirect(route('user.subscriptions.module-usages.unauthorized'));
            }
        }

        return parent::render($request, $e);
    }
}
