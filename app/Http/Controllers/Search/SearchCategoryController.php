<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchCategoryController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'categories' => Category::where('title', 'LIKE',"%{$request->q}%")
            ->simplePaginate(10)
        ]);
    }
}
