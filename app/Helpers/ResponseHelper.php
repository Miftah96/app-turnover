<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class ResponseHelper
{
    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function successResponse($data, $message = "Data successfully retrieved", $code = 200)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => $code,
            'message' => $message,
            'payload' => $data
        ];

        return response($response, $code);
    }

    /**
     * @param \Exception $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function failedResponse(\Exception $exception)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage()
        ];

        return response($response);
    }

    /**
     * @param $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function notFoundResponse($message)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => 404,
            'message' => $message
        ];

        return response($response, 404);
    }
    /**
     * @param $message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public static function unprocessableResponse($message, $code = 400)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => $code,
            'message' => $message
        ];

        return response($response, $code);
    }
    
    public static function clientErrorResponse($message, $code = 400)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => $code,
            'message' => $message
        ];

        return response($response, $code);
    }

    public static function deleteResponse($message = "Data successfully Deleted", $code = 200)
    {
        $response = [
            'url' => URL::full(),
            'method' => Request::getMethod(),
            'code' => $code,
            'message' => $message,
        ];

        return response($response, $code);
    }
}
