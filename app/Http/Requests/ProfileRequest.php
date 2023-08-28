<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'username' => ['required', "unique:users,username,{$this->id}", "regex:/^[a-z0-9_-]{3,15}$/"],
            'email' => "required|email|unique:users,email,{$this->id}",
            'profile' => 'nullable',
            'bio' => 'nullable',
            'password' => 'nullable',
            'email_on_follow' => 'nullable',
            'email_on_like' => 'nullable',
            'email_on_reply' => 'nullable'
        ];
    }
}
