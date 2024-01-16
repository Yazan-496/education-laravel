<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function uploadImage(Request $request)
    {

        $uploadedFile = $request->file('image');

        if (!$uploadedFile) {
            return response()->json(['message' => 'No image file provided'], 400);
        }

        $path = 'images';

        $filename = "img-" . time() . '.' . $uploadedFile->getClientOriginalExtension();

        $uploadedFile->storeAs($path, $filename);

        return response()->json(['message' => 'Image uploaded successfully', 'photo' => $filename], 200);
    }
}
