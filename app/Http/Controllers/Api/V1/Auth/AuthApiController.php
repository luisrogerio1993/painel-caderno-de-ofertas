<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\User;

class AuthApiController extends Controller {

    public function authenticate(Request $request) {
        if ( (!$request->has('email') || !$request->has('password')) && ((!$request->has('email') || !$request->has('account_social_id'))) ) {
            return response()->json(['error' => 'inputs_missing'], 401);
        }
        
        // grab credentials from the request
        if($request->has('password')){ //login normak
            $credentials = $request->only('email', 'password');
        }else{ //login social
            if( !$credentials = User::where('email', $request->get('email'))->where('account_social_id', $request->get('account_social_id'))->first() ){
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function refreshToken(Request $request) {
        if (!$token = $request->header('token')) {
            return response()->json(['error' => 'token_not_send'], 401);
        }

        try {
            JWTAuth::setToken($token);
            $token = JWTAuth::refresh();
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        }
        return response()->json(compact('token'));
    }
}
