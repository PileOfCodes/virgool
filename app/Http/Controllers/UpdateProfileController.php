<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class UpdateProfileController extends Controller
{
    public function update(ProfileRequest $request)
    {
        $data = $request->only([
            'name','username','email','profile','bio','password',
            'email_on_follow','email_on_like','email_on_reply'
        ]);
        if(empty($data['password']))
        {
            unset($data['password']);
        }else {
            $data['password'] = Hash::make($request->password);
        }

        if($request->profile && $request->profile_name)
        {
            $file_name = $request->user()->username . '.' . Str::afterLast($request->profile_name, '.');
            $path = public_path('profiles/') . $file_name;
            Image::make($request->profile)->fit(100)->save($path);  
            $data['profile'] = $file_name;
        }

        $request->user()->update($data);
        return $request->user();
    }
}
