<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected function success($data = [], $status = Response::HTTP_OK) {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status);
    }

    protected function fail($data = [], $status = Response::HTTP_BAD_REQUEST) {
        return response()->json([
            'success' => false,
            'data' => $data
        ], $status);
    }
}
