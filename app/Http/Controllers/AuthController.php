<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        $this->validate($request, [
            'username' => 'required|min:3|max:255',
            'password' => 'required|min:8|max:255'
        ]);
        
        $credentials = $request->only(['username', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'code' => 401,
                'message' => 'unauthorized'
            ], 401);
        }
        return $this->responseWithToken($token);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'code' => 200,
            'message' => 'successufully logout'
        ]);
    }

    public function responseWithToken($token) {
        return response()->json([
            'code' => 200,
            'message' => 'successufully login',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => time() + 60 * 60
        ]);
    }
}
