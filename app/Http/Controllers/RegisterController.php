<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Laravel\Sanctum\Sanctum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $register;

    public function __construct(User $user)
    {
        $this->register = $user;
    }

    public function register(UserRequest $request)
    {
        //Create user, generate token and return
        $register = $this->register->create( [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
        ] );

        if ($request->image)
        {
            $register->image = $request->file('image')->store('usersProfileImages','public');
            $register->save();
        }

        return new UserResource($register);


    }
}
