<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;

class BaseController extends Controller
{
    public function successResponse($result = [], $data = null)
    {
        $response = [
            'result' => $result,
        ];
        if ($data){
            $response['extra'] = $data;
        }
        return response()->json($response, 200);
    }

    public function errorResponse($result = [], $error)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data' => $result
        ];
        return response()->json($response);
    }



}
