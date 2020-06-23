<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use JD\Cloudder\Facades\Cloudder;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function image(Request $request)
    {
        $name = Hash::make($request->file('image'));
        $imageName = $request->file('image')->getRealPath();
        $publicId ="hardware-store/profileImages/user$name";


        Cloudder::upload($imageName, $publicId);

        list($width, $height) = getimagesize($imageName);

        $imageURL = Cloudder::secureShow($publicId, [
            "width" => $width,
            "height" => $height,
        ]);

        return $imageURL;
    }
}
