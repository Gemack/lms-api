<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                "photo"=> "mimes:png,jpg",
                "firstname"=> "required|min:2",
                "lastname"=> "required|min:2",
                "username"=> "required|string|unique:users|max:10",
                "email"=> "required|email|unique:users",
                "role_id"=> "required",
                "password"=> "required|confirmed",

    
        ];
    }
    
}