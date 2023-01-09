<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //  if the Teacher is the owner of the course or the user is admin
    public function DeleteOrUpdate(User $user, Course $course){
        return $user->id  === $this->user_id || $this->user->role_id ===3;
    }

    // Only admin and teacher can create a course
    public function Create(User $user, Course $course){
        return $user->role_id === 2 || 3;
    }

}