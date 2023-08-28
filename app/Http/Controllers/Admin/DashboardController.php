<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return response([
            "data" => DB::select('select 
                (select count(*) from users) as users_count,
                (select count(*) from posts) as posts_count,
                (select count(*) from categories) as categories_count,
                (select count(*) from comments) as comments_count'
            )[0]
        ], 200);
    }
}
