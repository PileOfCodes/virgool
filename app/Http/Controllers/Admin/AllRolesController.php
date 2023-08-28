<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class AllRolesController extends Controller
{
    public function index()
    {
        return response(Role::all(), 200);
    }
}
