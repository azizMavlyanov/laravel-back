<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function index()
    {
        return Photo::all();
    }

    public function show(Photo $photo)
    {
        return $photo;
    }

    public function store(Request $request)
    {   
        if (!$request->hasFile('image_file')) {
            return response()->json(['error' => 'Uploaded file not found'], 400);
        }

        $allowedfileExtension=['pdf','jpg','png'];
        $file = $request->file('image_file'); 
        $extension = $file->getClientOriginalExtension();
        $check = in_array($extension,$allowedfileExtension);
        
        if ($check) {
            $path = $file->store('images', 'public_uploads');
            $name = $file->getClientOriginalName();

            $save = new Photo();
            $save->title = $name;
            $save->image_path = '/uploads/'.$path;
            $save->save();

            return response()->json(['photo' => $save], 200);
        } else {
            return response()->json(['error' => 'Invalid file format'], 422);
        }
    }

    public function update(Request $request, Photo $photo)
    {
        $photo->update($request->all());

        return response()->json($photo, 200);
    }

    public function delete(Photo $photo)
    {
        $photo->delete();

        return response()->json(null, 204);
    }

}
