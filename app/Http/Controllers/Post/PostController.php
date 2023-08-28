<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(PostRequest $request)
    {
        $data = $request->only(['title','content','description','image','min_read']);
        $data['description'] = Str::words($data['description'], 50, '...') ?? Str::words(strip_tags($data['content']), 50, '...');
        $data['user_id'] = $request->user()->id;
        $word = count(explode(' ',strip_tags($data['content'])));
        $data['min_read'] = ceil($word / 200);
        if($request->image && $request->image_name)
        {
            $imageName = time() . '.' . Str::afterLast($request->image_name, '.');
            $path = public_path('posts/') . $imageName;
            Image::make($request->image)->save($path);  
            $data['image'] = $imageName;
        }

        $post = Post::create($data);
        $categories = [];
        $selectedCategories = Category::whereIn('title', $request->categories)->get();
        $shoudCreateCategory = collect($request->categories)->diff($selectedCategories->pluck('title'));
        foreach ($shoudCreateCategory->toArray() as $categoryTitle) {
            $categories[] = Category::create(['title' => $categoryTitle]);
        }
        $post->categories()->attach(
            collect($categories)->pluck('id')->concat($selectedCategories->pluck('id'))
        );   

        return response(['data' => $post], 200);
    }

    public function show(Post $post) 
    {
        $this->authorize('view', $post);
        return $post;
    }

    public function edit(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $post->update($data);
        return $post;
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->only(['title','content','description','image','min_read']);
        $data['description'] = Str::words($data['description'], 50, '...') ?? Str::words(strip_tags($data['content']), 50, '...');
        $data['user_id'] = $request->user()->id;
        $word = count(explode(' ',strip_tags($data['content'])));
        $data['min_read'] = ceil($word / 200);
        if($request->image && $request->image_name)
        {
            $imageName = time() . '.' . Str::afterLast($request->image_name, '.');
            $path = public_path('posts/') . $imageName;
            Image::make($request->image)->save($path);  
            $data['image'] = $imageName;
        }

        $post->update($data);
        $createdCategories = [];
        $selectedCategories = Category::whereIn('title', $request->categories_title)->get();
        $shoudCreateCategory = collect($request->categories_title)->diff($selectedCategories->pluck('title'));
        foreach ($shoudCreateCategory->toArray() as $categoryTitle) {
            $createdCategories[] = Category::create(['title' => $categoryTitle]);
        }
        if($createdCategories != [])
        {
            $post->categories()->attach(
                collect($createdCategories)->pluck('id')->concat($selectedCategories->pluck('id'))
            );
        }

        return response(['data' => $data], 200);
    }

    public function destroy(Post $post)
    {
        $this->authorize('view', $post);
        $post->delete();
        return response([
            'data' => 'ok'
        ], 200);
    }
}
