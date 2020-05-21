<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
       //$data = $request->getContent();
        //dd(json_decode($data));
        $user = $this->user->create( $request->all() );

        if ($request->image)
        {
            $user->image = $request->file('image')->store('usersProfileImages','public');
            $user->save();
        }


        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        if ($request->image)
        {
            Storage::disk('public')->delete($user->image);
            $user->image = $request->file('image')->store('imagenes','public');
            $user->save();
        }

        return new UserResource($user);
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

        return response()->json('Eliminado con éxito', 204);
    }
}
