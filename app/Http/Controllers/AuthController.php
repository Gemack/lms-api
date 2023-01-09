<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // Student registration

    public function RegisterStudent(Request $request){
        //  Validate incomming request
        $data = Validator::make($request->all(),[
        'photo'=> 'mimes:jpg,jpeg,png',
        'firstname'=>['required', 'min:2'],
        'lastname'=>['required', 'min:2'],
        'username'=>['required', 'min:2'],
        'email'=>['required', 'email', Rule::unique('users', 'email')],
        'password'=>'required|confirmed|min:2'  // min:2 is for testing this will be expanded //

        ]);

        if($data->fails()){
            return response($data->errors(), 400);
        }
        
        //  Hash the password of the Student
        $request['password']= bcrypt($request['password']);
        $request['role_id']= 1;    
 
        //  Check if the request has a photo and save.
        if($request->hasFile('photo')){
        $request['photo'] = $request->file('photo');
        $extention = $request['photo']->getClientOriginalExtension();
        $filename =time().'.'.$extention;
        Image::make( $request['photo'])->resize(300, 200)->save('profile/'. $filename, 100);
        $request['photo']='profile/'. $filename;

          //  Save User into the database with photo
          $user =User::create([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'username' => $request['username'],
            'email' => $request['email'],
            'role_id'=>1,
            'password' => $request['password'],
            'photo' => 'profile/'. $filename,
        ]);

        }   else{
            //  Save User into the database without image
            $user =User::create($request->all());
        }
     
        //  Generate access token for registered student
        $token = $user->createToken($user['email'])->plainTextToken;
        $response =[
            "user"=>$user,
            "token"=>$token
       ];
       return response($response, 200);
    }    
 

    public function RegisterTeacher(Request $request)
    {
        //  Validate Incomming request
        $data = Validator::make($request->all(),[
            'firstname'=>['required', 'min:2'],
            'lastname'=>['required', 'min:2'],
            'username'=>['required', 'min:2'],
            'email'=>['required', 'email', Rule::unique('users', 'email')],
            'password'=>'required|confirmed|min:2'  // min:2 is for testing this will be expanded //
    
            ]);

            if($data->fails()){
                return response($data->errors(), 400);
            }
            
            //  Hash the password of the Teacher
            $request['password']= bcrypt($request['password']);
            $request['role_id']= 2;    
            
            //  Save User into the database
            $user =User::create($request->all());    
            
            return response($user, 201);
    }

    public function login(Request $request){

        if(auth()->attempt(["email"=>$request->email, "password"=>$request->password])){
            $user = auth()->user();
            $token = $user->createToken($user['email'])->plainTextToken;
            $success = ["message"=>"logged in successful","user"=>$user, "token"=>$token];
            return response($success, 200);
        }else{
            return response(["message"=>"Email or Password incorrect"], 400);
        };


    }
    public function logout(Request $request){ 
        
        // Delete current user token
        auth()->user()->tokens()->delete();
        $response =[
            "message"=>'You have been logged out'
       ];
       return response($response, 200);}

}