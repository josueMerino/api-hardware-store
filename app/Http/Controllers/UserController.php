<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResourceCollection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;

class UserController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function rules()
    {
        return [
            'name' => 'string',
            'last_name' => 'string',
            'email' => 'email|unique:users',
            'image' => 'nullable|image|mimes:jpeg,bmp,jpg,png',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserResourceCollection($this->user->get());
    }

    /**
     * The method store it's in RegisterController
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //dump($user);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
           //dump($request);
        $validator = Validator::make($request->all(), $this->rules());

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]+ $request->all());

        if ($request->file('image') && $request->image)
        {
            // Delete the file from the folder where it's stored
            Storage::disk('public')->delete($user->image);

            $user->image = $request->file('image')->store('usersProfileImages','public');
            $user->image = storage_path($user->image);
            $user->save();
        }

        return new UserResource($user);
        } catch (Exception $error)
        {
            return response()->json([
                'message' => 'There was an error',
                'error' => $error,
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' =>'Eliminado con éxito'
            ], 200);
    }
}
