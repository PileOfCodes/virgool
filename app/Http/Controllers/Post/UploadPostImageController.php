<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadPostImageController extends Controller
{
    public function getPostImage(Request $request)
    {   
        $image = $request->file('file');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('/images/posts', $imageName, 'public');

        return response([
            'data' => $imageName
        ]);
    }
}
