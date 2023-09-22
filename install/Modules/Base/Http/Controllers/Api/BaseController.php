<?php

namespace Modules\Base\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function setCookieResponse($message, $name, $value, $duration, $code = Response::HTTP_OK) 
    {
        $response = [
            'success' => true,
            'message' => utf8_encode($message)         
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, $code)->cookie(
            $name, 
            $value, 
            $duration
        );
    }
    
    /**
     * Success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($message, $result = [], $code = Response::HTTP_OK) 
    {
        $response = [
            'success' => true,
            'message' => utf8_encode($message)         
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, $code);
    }

    /**
     * Error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($message, $result = [], $code = Response::HTTP_BAD_REQUEST) 
    {
        $response = [
            'success' => false,
            'message' => utf8_encode($message)
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, $code);
    }

    /**
     * Forbidden error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorForbidden($message = 'This action is unauthorized.') 
    {
        $response = [
            'success' => false,
            'message' => utf8_encode($message)
        ];

        return response()->json($response, Response::HTTP_FORBIDDEN);
    }

    /**
     * Internal error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorInternalError($message = 'Internal Error.') 
    {
        $response = [
            'success' => false,
            'message' => utf8_encode($message)
        ];

        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Not found error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorNotFound($message = 'Resource Not Found.') 
    {
        $response = [
            'success' => false,
            'message' => utf8_encode($message)
        ];

        return response()->json($response, Response::HTTP_NOT_FOUND);
    }

    /**
     * Unauthorized error response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorUnauthorized($message = 'Unauthorized.') 
    {
        $response = [
            'success' => false,
            'message' => utf8_encode($message)
        ];

        return response()->json($response, Response::HTTP_UNAUTHORIZED);
    }
}
