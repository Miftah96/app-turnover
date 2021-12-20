<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Hash;
use JWTAuth;
use Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public $token = true;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), 
                    [ 
                        'name'      => 'required',
                        'user_name' => 'required',
                        'password'  => 'required',   
                    ]);  
        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        }   
        
        $user   = new User();
        $user->name = $request->name;
        $user->user_name    = $request->user_name;
        $user->password     = Hash::make($request->get('password'));
        $user->created_by = 1;
        $user->updated_by = 1;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function login(Request $request)
    {
        $data       = $request->only('user_name', 'password');
        $jwt_token  = null;
        if (!$jwt_token = JWTAuth::attempt($data)) {
            return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
}
