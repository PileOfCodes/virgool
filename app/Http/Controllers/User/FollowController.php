<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserFollowNotification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, User $user)
    {
        $request->user()->follows()->toggle([$user->id]);
        if($user->is_followed)
        {
            $user->notify(new UserFollowNotification($request->user()));
        }
        return response(['data' => 'ok'], 200);
    }
}
