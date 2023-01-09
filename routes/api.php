<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;


//  Registration Route
Route::post('student/registration',[AuthController::class, 'RegisterStudent']);
Route::post('teacher/registration',[AuthController::class, 'RegisterTeacher'])->middleware(['auth:sanctum', 'is_admin']);

// Login and out Route
Route::post('login',[AuthController::class, 'login']);
Route::post('logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');


// Course Route
Route::post('course/create',[CourseController::class, 'store'])->middleware(['auth:sanctum', 'is_teacher', 'is_admin']);
Route::post('course/update/{id}',[CourseController::class, 'update'])->middleware(['auth:sanctum', 'is_teacher', 'is_admin']);
Route::delete('course/delete/{id}', [CourseController::class, 'delete'])->middleware('auth:sanctum')->middleware(['auth:sanctum', 'is_teacher', 'is_admin']);;
Route::post('course/enroll/{course}',[CourseController::class, 'enroll'])->middleware(['auth:sanctum', 'is_student']);
Route::post('course/comments/{course}',[CourseController::class, 'comments'])->middleware(['auth:sanctum', 'is_student']);
Route::post('course/rate/{course}',[CourseController::class, 'rating'])->middleware(['auth:sanctum', 'is_student']);


// User Route
Route::get('user/all', [UserController::class, 'index'])->middleware('auth:sanctum')->middleware('auth:sanctum');
Route::get('user/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::get('user/student/{role}', [UserController::class, 'ShowStudent'])->middleware('auth:sanctum');
Route::get('user/teacher/{role}', [UserController::class, 'ShowTeachers'])->middleware('auth:sanctum');