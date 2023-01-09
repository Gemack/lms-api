<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('role')->get();
        return response($user, 200);
    }

    public function ShowStudent()
    {
        $role =1;
        $user = User::where('role_id', $role)->get();
        return response($user, 200);
    }
    public function ShowTeachers()
    {
        $role = 2;
        $user = User::where('role_id', $role)->get();
        return response($user, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, $id)
    {
        $user = User::where('id',$id)->get();
        return response($user, 200);
    }


    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(),[
            'photo'=> 'mimes:jpg,jpeg,png',
            'firstname'=>['required', 'min:2'],
            'lastname'=>['required', 'min:2'],
            'username'=>['required', 'min:2'],
            'email'=>['required', 'email', Rule::unique('users', 'email')],   
            ]);
    
            if($data->fails()){
                return response($data->errors(), 400);
            };

        // Check if the user has a profile picture, resize and save picture in server
        if($request->hasFile('photo')){
        $file = $request->file('photo');
        // get the file extension
        $extention = $file->getClientOriginalExtension();
        $filename =time().'.'.$extention;
        //  image resizing 
        Image::make($file)->resize(300, 200)->save('profile/'. $filename, 100);
       

        // Find and delete old image
        $user =User::find($id);
        $old_img =$user->photo;
     
        if($old_img){
            unlink($old_img);
        }     
        
        // update user with the new profile picture if there is a new profile picture
            $user->update([
                'firstname' => $request['firstname'] ?? $user->firstname,
                'lastname' => $request['lastname']?? $user->lastname,
                'username' => $request['username']?? $user->username,
                'email' => $request['email']?? $user->username,
                'role_id'=> $request->user->role_id,
                "photo"=> 'profile/'. $filename 
        ]);
            }
        //  Update user without profile picture
            $user =User::find($id);
            $user->update([
            'firstname' => $request['firstname'] ?? $user->firstname,
            'lastname' => $request['lastname']?? $user->lastname,
            'username' => $request['username']?? $user->username,
            'email' => $request['email']?? $user->username,
            'role_id'=> $request->user->role_id,
        ]);
            

            
    }

    public function destroy($id)
    {
        
        $user =User::find($id);
        $old_img = $user->photo;
        unlink($old_img);   
        User::find($id)->delete();
        return response("User Deleted", 204);
           
    }
}