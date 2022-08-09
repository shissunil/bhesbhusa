<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    public function sendResponse($statusCode, $result, $message)
    {
        $response = [
            'status' => $statusCode,
            // 'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
    
    public function paginatResponse($statusCode, $result , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, $message)
    {
        $response = [
            'status' => $statusCode,
            'data' => $result,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'pageNumber' => $pageNumber,
            'hasNextPage' => $nextPage,
            'hasPreviousPage' => $prevPage,
            'message' => $message,
        ];
        
        return response()->json($response, 200);
    }
    
    public function sendError($statusCode, $error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => $statusCode,
            // 'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
