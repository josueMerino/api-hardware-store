<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Laravel\Sanctum\Sanctum;
use JD\Cloudder\Facades\Cloudder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
            'image' => 'nullable|image|mimes:jpeg,bmp,jpg,png',
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

            // If the request has an image we will save it
            if ($request->file('image') && $request->image)
            {
                //Now we assign variables

                //Name is to make a unique id to the image
                $name =Hash::make($request->file('image')->getClientOriginalName(),[
                    'salt' => 12,
                ]);
                $imageName = $request->file('image')->getRealPath();
                $publicId ="hardware-store/profileImages/user/";


                Cloudder::upload($imageName, null,[
                    'path' => $publicId,
                ]);

                list($width, $height) = getimagesize($imageName);

                $imageURL = Cloudder::secureShow(Cloudder::getPublicId(), [
                    "width" => $width,
                    "height" => $height,
                ]);

                $register->image = $imageURL;
                $register->image_path = Cloudder::getPublicId();
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

    /*public function saveImages(Request $request, $image_url)
    {
        $image = new UploadedFile();
        $image->image_name = $request->file('image_name')->getClientOriginalName();
        $image->image_url = $image_url;

        $image->save();
    }*/
}
