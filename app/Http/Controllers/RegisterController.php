<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Laravel\Sanctum\Sanctum;
use Exception;
use Illuminate\Http\Request;


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
        $register = $this->register->create( $request->all() );

            if ($request->image)
            {
                $register->image = $request->file('image')->store('usersProfileImages','public');
                $register->save();
            }

        return new UserResource($register);


    }
}
