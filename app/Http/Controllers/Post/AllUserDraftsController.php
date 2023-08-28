<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllUserDraftsController extends Controller
{
    public function getAllDrafts(Request $request)
    {
        
        return response([
            'data' => $request->user()->drafts->map(function ($draft) {
            return collect($draft)->merge([
                'updated_at' => $draft->updated_at->diffForHumans()]);
            }),
            'drafts_count' => $request->user()->drafts->count()
        ], 200);
    }
}
