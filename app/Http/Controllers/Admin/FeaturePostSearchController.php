<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FeaturePostSearchController extends Controller
{
    public function index(Request $request)
    {
        return response([
            "data" => Post::query()->where(function($q) use($request) {
                $q->where('title', 'LIKE', "%{$request->q}%")
                ->orWhere('content', 'LIKE', "%{$request->q}%");
            })
                ->doesntHave('FeaturePost')
                ->take(10)
                ->get()
        ]);
    }
}
