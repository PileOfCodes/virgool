<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Models\User;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index(Request $request)
    {
        $draftFields = collect(Draft::$FIELDS);
        $sort_by = $draftFields->keys()
        ->contains($request->sort_by) ? $request->sort_by : 'id' ;

        $sort_type = $request->sort_type === 'asc' ? 'asc' : 'desc';

        $per_page = $request->per_page ?? 5;

        $headers = $draftFields->transform(function ($item, $key) {
            return ['text' => $item, 'value' => $key];
        });

        if($request->search == null)
        {
            $drafts = Draft::with('user')
            ->orderBy($sort_by,$sort_type)
            ->orderBy(User::select('name')->whereColumn('users.id','drafts.user_id'), $sort_type)
            ->paginate($per_page);
        }else {
            $drafts = Draft::with('user')->orderBy($sort_by,$sort_type)
            ->orderBy(User::select('name')->whereColumn('users.id','drafts.user_id'), $sort_type)
            ->where('title', 'LIKE',"%{$request->search}%")
            // ->orWhere('content', 'LIKE',"%{$request->search}%")
            ->orWhereHas('user', function($q) use($request) {
                $q->where('name','LIKE',"%{$request->search}%");
            })
            ->paginate($per_page);
        }

        return response()->json([
            'drafts' => $drafts,
            'headers' => $headers
        ]);
    }


    public function destroy(Draft $draft)
    {
        $draft->delete();
        return response([
            "data" => "ok"
        ], 200);
    }
}
