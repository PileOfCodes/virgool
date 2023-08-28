<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryFields = collect(Category::$FIELDS);
        $sort_by = $categoryFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 5;

        $headers = $categoryFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $categories = Category::withCount('posts')
            ->orderBy($sort_by,$sort_type)
            ->paginate($per_page);
        }else {
            $categories = Category::withCount('posts')
            ->orderBy($sort_by,$sort_type)
            ->where('title', 'LIKE',"%{$request->search}%")
            ->paginate($per_page);
        }

        return response()->json([
            'categories' => $categories,
            'headers' => $headers
        ]);
    }


    public function store(Request $request)
    {
        $request->validate(['title' => 'required']);
        $category = Category::firstOrCreate(['title' => $request->title]);
        return response([
            'data' => $category
        ], 200);
    }

    public function show(Category $category)
    {
        return response([
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['title' => 'required']);
        $category = $category->update(['title' => $request->title]);
        return response([
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return response([
            'date' => 'ok'
        ], 200);
    }
}
