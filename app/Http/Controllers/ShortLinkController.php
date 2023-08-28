<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function __invoke(Post $post)
    {
        return redirect("post/$post->slug");
    }
}
