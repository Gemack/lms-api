<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class CourseController extends Controller
{
    public function index(){
        $courses = Course::get();

        return response($courses, 200);
    }

    public function store(Request $request)
    {
        //  Check if the user is authorized to make this action
        $this->authorize('Create');
        //  Validate the incomming request 
        $data = Validator::make($request->all(),[
            'photo'=> 'mimes:jpg,jpeg,png',
            'title' =>'required',
            'description'=>'required',
            'user_id' => 'required',
        ]);

        if($data->fails()){
            return response($data->errors(), 400);
        }

        $request['photo']= $request->file('photo');
        $extention = $request['photo']->getClientOriginalExtension();
        $filename =time().'.'.$extention;
        Image::make( $request['photo'])->resize(300, 200)->save('courses/'. $filename, 100);


        $course = Course::create([
            "photo"=> 'courses/'. $filename,
            "title"=> $request['title'],
            "description"=> $request['description'],
            "user_id"=>$request["user_id"],
        ]);

        return response($course, 201);
 
    }

    public function show($id){
        $course = Course::where("id", $id)->get();
    }

    public function update(Request $request, $id)
    {
        // Check if the user is authorized to perform this action
        $this->authorize('DeleteOrUpdate', $id);
        //  Validate incommming request
        $data = Validator::make($request->all(),[
            'photo'=> 'mimes:jpg,jpeg,png',
            'title' =>'required',
            'description'=>'required',
            'user_id' => 'required',
        ]);
        $course = Course::find($id);
        if($request->hasFile('photo')){
            $request['photo']= $request->file('photo');
            $extention = $request['photo']->getClientOriginalExtension();
            $filename =time().'.'.$extention;
            Image::make( $request['photo'])->resize(300, 200)->save('courses/'. $filename, 100);
            $old_img = $course->photo;
            unlink($old_img);
            // update course if photo is updated
            $course->update([
                "photo"=> 'courses/'. $filename,
                "title"=> $request['title']?? $course->title,
                "description"=> $request['description']?? $course->description,
                "user_id"=>$request["user_id"]?? $course->user_id,
            ]);
        }
        $course->update([
            "photo"=> $course->photo,
            "title"=> $request['title']?? $course->title,
            "description"=> $request['description']?? $course->description,
            "user_id"=>$request["user_id"]?? $course->user_id,
        ]);

        return response($course, 201);
    }
    public function delete($id){
        $this->authorize('DeleteOrUpdate', $id);
        $course = Course::find($id);
        $old_img = $course->photo;
        unlink($old_img);
        Course::find($id)->delete();
        return response("User Deleted", 204);

    }

    //  This function enroll a student to a
    public function enroll(Course $course, Request $request){
        if($course->AlreadyEnrolled($request->user())){
            return response(["message"=>"already enrolled for this course"], 400);
        };
        $course->enrollment()->create([
            'user_id'=> $request->user()->id
        ]);
    }

    public function comments(Course $course, Request $request){

        $data =  Validator::make($request->all(),[
                "comments" => "required",
        ]);
        
        if($data->fails()){
            return response($data->errors(), 400);
        }

        $course->comments()->create([
            "user_id" => $request->user()->id,
            "comments"=> $request["comments"]
        ]);

        return response(201);
    }

    public function rating(Course $course, Request $request){
        $data =  Validator::make($request->all(),[
            "rating" => "required",
    ]);
    if($data->fails()){
        return response($data->errors(), 400);
    }
        $course->ratings()->create([
            "user_id" => $request->user()->id,
            "rating"=> $request["rating"]
        ]);
    }

}