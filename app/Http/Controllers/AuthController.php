<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Student registration

    public function StudentRegistration(UserRequest $request){

        
    }
}

// public function store(Request $request){
//     $formFields = $request->validate([
//         'name'=>['required', 'min:2'],
//         'email'=>['required', 'email', Rule::unique('users', 'email')],
//         'photo'=> 'mimes:jpg,jpeg,png',
//         'password'=>'required|confirmed|min:2'  // min:2 is for testing this will be expanded //
//     ]);

//     // Hash password
//     $formFields['password']= bcrypt($formFields['password']);
//     # Profile photo upload and resizing 
//     if($request->hasFile('photo')){
//         $formFields['photo'] = $request->file('photo');
//         $extention = $formFields['photo']->getClientOriginalExtension();
//         $filename =time().'.'.$extention;
//         Image::make( $formFields['photo'])->resize(300, 200)->save('profile/'. $filename, 100);
//         $formFields['photo']='profile/'. $filename;
//     }


//     $user =User::create($formFields);
//     // login 
//     auth()->login($user);
//     return redirect('/');
// }