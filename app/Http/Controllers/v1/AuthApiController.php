<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = auth()->user();

        // all good so return the token
        return response()->json(compact('token', 'user'));
    }

    // somewhere in your controller
    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired']);
        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid']);
        } catch (JWTException $e) {

            return response()->json(['token_absent']);
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['error' => 'token_not_send'], 401);
        }

        try {
            $token = JWTAuth::refresh();
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid']);
        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('token'));
    }
}
