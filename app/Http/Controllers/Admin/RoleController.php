<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roleFields = collect(Role::$FIELDS);
        $sort_by = $roleFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 5;

        $headers = $roleFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $roles = Role::orderBy($sort_by,$sort_type)->paginate($per_page);
        }else {
            $roles = Role::orderBy($sort_by,$sort_type)
            ->where('name', 'LIKE',"%{$request->search}%")
            ->paginate($per_page);
        }

        return response()->json([
            'roles' => $roles,
            'headers' => $headers
        ]);
    }

    public function show(Role $role)
    {
        return response([
            'role' => $role
        ], 200);
    }

    public function store(Request $request,)
    {
        $request->validate(['name' => 'required|max:255']);
        Role::create($request->only('name'));
        return response([
            'data' => 'ok'
        ], 200);
    }

    public function update(Request $request,Role $role)
    {
        $request->validate(['name' => 'required|max:255']);
        $role->update($request->only('name'));
        return response([
            'data' => 'ok'
        ], 200);
    }
}
