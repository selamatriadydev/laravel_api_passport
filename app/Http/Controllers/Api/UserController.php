<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $user;
    public function __construct(){
        $this->middleware("auth:api",["except" => ["login","register", "viewProfile"]]);
        $this->user = new User;
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if($validator->fails()){
            return $this->sendError("Validasi", $validator->messages()->toArray(), 500);
        }
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "remember_token" => Str::random(10)
        ];
        $userCreate = $this->user->create($data);
        $accessToken = $userCreate->createToken('authTokenBlog')->accessToken;
        $responseMessage = "Registration Successful";
        return $this->sendResponse("", $responseMessage);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string',
            'password' => 'required|min:6',
        ]);
        if($validator->fails()){
            return $this->sendError("validasi", $validator->messages()->toArray(), 500);
        }
        $credentials = $request->only(["email","password"]);
        $user = User::where('email',$credentials['email'])->first();
        if($user){
            if(!auth()->attempt($credentials)){
                $responseMessage = "Invalid username or password";
                return $this->sendError($responseMessage, $responseMessage, 422);
            }
            $accessToken = auth()->user()->createToken('authTokenBlog')->accessToken;
            $responseMessage = "Login Successful";
            return $this->respondWithToken($accessToken,$responseMessage,auth()->user());
        }
        else{
            $responseMessage = "Sorry, this user does not exist";
            return $this->sendError($responseMessage, $responseMessage, 422);
        }
    }

    public function viewProfile(){
        if (Auth::guard('api')->check()) {
            $responseMessage = "user profile";
            $data = Auth::guard("api")->user();
            return $this->sendResponse($data, $responseMessage);
        }
        return $this->sendError("Unauthenticated", "", 500);
    }

    public function logout(){
        $user = Auth::guard("api")->user()->token();
        $user->revoke();
        $responseMessage = "successfully logged out";
        return $this->sendResponse("", $responseMessage);
    }
}
