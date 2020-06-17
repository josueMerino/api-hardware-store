<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request){

    try {

        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
            ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = User::all()->where('email', $request->email)->first();

            if ( !Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('token', ['server:update'])->plainTextToken;

            return response()->json([
            'access_token' => $tokenResult,
            ], 200);
        }
        else
        {
            // When validation is fulfilled but is wrong the message is shown
            return response()->json([
                'message' => 'Wrong Email or/and Password, try again'
                ], 401);
        }
    }
    catch (Exception $error)
    {
    return response()->json([
    'message' => 'Error in Login, please insert a valid email and password',
    'error' => $error,
    ], 400);
    }

    }

    public function logout(User $user)
    {
        try {
                $user->tokens()->where('id', $user->id)->delete();
                Auth::guard('web')->logout();
                return response()->json(['success' =>'logout_success'],200);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'System Error',
                'error' => $error,
            ]);
        }

    }
}
