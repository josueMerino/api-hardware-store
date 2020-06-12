<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($folder, $file)
    {
        $path = $folder.'/'.$file;

        dd(storage_path('app/public/'.$path));
        //return response()->file();

    }
}
