<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($folder, $file)
    {
        $path = $folder.'/'.$file;
        return response()->file(app_path('storage/app/public/'.$path));

    }
}
