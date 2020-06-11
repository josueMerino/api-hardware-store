<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Laravel\Sanctum\Sanctum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    protected $register;

    public function __construct(User $user)
    {
        $this->register = $user;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'birth_date' => 'required',
            'country' => 'required',
            'password' => 'required',
        ];
    }

    public function register(Request $request)
    {

        try {
            // We validate data
            $validator = Validator::make($request->all(), $this->rules());

            // If data isn't right we show a json with the errors
            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors()],422);
            }


            //Create user, save in DB and return
            $register = $this->register->create([
                'password' => Hash::make($request->password),
            ] + $request->all() );

            //dd($request);

            if ($request->file('image') && $request->image)
            {
                $register->image = $request->file('image')->store('usersProfileImages','public');
                $register->save();
            }

            return new UserResource($register);
        } catch (Exception $error)
        {
            return response()->json([
            'message' => 'Error in Register',
            'error' => $error,
            ], 500);
        }



    }
}
