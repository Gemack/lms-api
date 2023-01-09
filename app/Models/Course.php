<?php

namespace App\Models;

use App\Models\Rating;
use App\Models\Comment;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo', 
        'title', 
        'description', 
        'user_id'
    ];

        //  This method prevent Student from enrolling more than once
    public function AlreadyEnrolled(User $user){
            return $this->enroll->contains('user_id', $user->id);
            
        }

    //  A Course can have multiple students enrolled
    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }

    //  A course can have many comments from many students
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // A course can have Many rating from many students
    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}