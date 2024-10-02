<?php

use App\Http\Controllers\API\TeacherApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ManagerApiContoller;
use App\Http\Controllers\API\ClassApiContoller;
use App\Http\Controllers\API\SubjectApiController;
use App\Http\Controllers\API\ClassSubjectApiController;
use App\Http\Controllers\API\ProjectApiController;
use App\Http\Controllers\API\AssignSubjectApiTeacherController;
use App\Http\Controllers\API\StudentApiController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register',[UserController::class,'register']);
Route::post('/auth/login', [UserController::class,'login']);
Route::post('/profile/change-password',[ProfileController::class,'change_password'])->middleware('auth:sanctum');
Route::post('/profile/update-profile',[ProfileController::class,'update_profile'])->middleware('auth:sanctum');

Route::get('/auth/user',[UserController::class,'user'])->middleware('auth:sanctum');
Route::get('/auth/logout',[UserController::class,'logout'])->middleware('auth:sanctum');

//MyStudents
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/students', [StudentApiController::class, 'list']);
    Route::get('/students/add', [StudentApiController::class, 'add']);
    Route::post('/students/insert', [StudentApiController::class, 'insert']);
    Route::get('/students/{id}/edit', [StudentApiController::class, 'edit']);
    Route::put('/students/{id}/edit', [StudentApiController::class, 'update']);
    Route::delete('/students/{id}', [StudentApiController::class, 'delete']);
    Route::get('/students/mystudents', [StudentApiController::class, 'myStudent']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/projects/insert', [ProjectApiController::class, 'insert']);
    Route::get('/project/{id}', [ProjectApiController::class, 'edit']);
    Route::put('/project/{id}', [ProjectApiController::class, 'update']);
    Route::delete('/project/{id}', [ProjectApiController::class, 'delete']);
    Route::delete('/projects/{id}/submit', [ProjectApiController::class, 'submit']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tasks/submit', [ProjectApiController::class, 'tasksubmit']);
    Route::get('/projects/{projectId}/tasks', [ProjectApiController::class, 'viewTasks']);
    Route::delete('/tasks/{projectId}/{taskId}/mark-as-done', [ProjectApiController::class, 'markTaskAsDone']);
});