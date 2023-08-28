<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAdminRequest;
use App\Http\Requests\UserAdminUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $userFields = collect(User::$FIELDS);
        $sort_by = $userFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 5;

        $headers = $userFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $users = User::withCount('posts')->orderBy($sort_by,$sort_type)->paginate($per_page);
        }else {
            $users = User::withCount('posts')->orderBy($sort_by,$sort_type)
            ->where('name', 'LIKE',"%{$request->search}%")
            ->orWhere('username', 'LIKE',"%{$request->search}%")
            ->orWhere('email', 'LIKE',"%{$request->search}%")
            ->orWhere('bio', 'LIKE',"%{$request->search}%")
            ->paginate($per_page);
        }

        return response()->json([
            'users' => $users,
            'headers' => $headers
        ]);
    }


    public function store(UserAdminRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        event(new Registered($user = User::create($data)));
        $user->roles()->sync($request->roles);
        return response([
            "data" => "ok"
        ]);
    }


    public function show(User $user)
    {
        return response([
            'user' => $user->append('all_roles_id')
        ]);
    }


    public function update(UserAdminUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        if(filled($data['password']))
        {
            $data['password'] = Hash::make($data['password']);
        }else {
            unset($data['password']);
        }
        $user->update($data);
        $user->roles()->sync($request->roles);
        return response([
            "data" => "ok"
        ]);
    }


    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response([
            'data' => 'ok'
        ], 200);
    }
}
