<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function respondWithToken($token, $responseMessage, $data){
        return \response()->json([
        "success" => true,
        "message" => $responseMessage,
        "data" => $data,
        "token" => $token,
        "token_type" => "bearer",
        ],200);
    }

    public function sendResponse($result, $message )
    {
    	$response = [
            'success' => true,
            'message' => $message,
        ];
        if(!empty($result)){
            $response['data'] = $result;
        }
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['validate'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function appAccess(){
        if (Auth::guard('api')->check()) {
            // $user = Auth::guard('api')->user();
            return true;
        }
        // if (Auth::user() !== null) { //alternatif
        //     return true;
        // }
        // return false;
        return $this->sendError("Unauthenticated", "", 500);
    }
}
