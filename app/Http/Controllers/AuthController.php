<?php

namespace App\Http\Controllers;

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
        if (!Auth::attempt($credentials))
        {

            // When validation is fulfilled but is wrong the message is shown
            return response()->json([
            'message' => 'Wrong Email or/and Password, try again'
            ], 401);
        }
        $user = User::all()->where('email', $request->email)->first();
        if ( !Hash::check($request->password, $user->password, [])) {
        throw new \Exception('Error in Login');
        }
        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
        'access_token' => $tokenResult,
        ]);
    }
    catch (Exception $error)
    {
    return response()->json([
    'message' => 'Error in Login, please insert a valid email and password',
    'error' => $error,
    ], 400);
    }

    }
}
