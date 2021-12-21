<?php

namespace App\Http\Controllers\Api;

use Hash;
use JWTAuth;
use JWTFactory;
use App\User;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


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
        $credential  = null;

        if (!$credential = JWTAuth::attempt($data)) {
            return response()->json([
                    'success' => false,
                    'message' => 'Invalid username or Password',
                ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        return response()->json([
            'token'     => $credential,
            'success'   => true,
            'user'      => $user
        ]);
    }
}
