<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentListController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectInvitationController;

// Public Routes
Route::get('/', [WebAuthController::class, 'loginuser'])->name('loginuser');  // Shows login form
Route::get('/registration', [WebAuthController::class, 'registration'])->name('registration');
Route::post('/login', [WebAuthController::class, 'Authlogin'])->name('login'); // Handles login
Route::post('/register', [WebAuthController::class, 'register'])->name('register');
Route::get('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [WebAuthController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('/forgot-password', [WebAuthController::class, 'PostForgotPassword'])->name('postForgotPassword');
Route::get('/reset/{token}', [WebAuthController::class, 'reset']);
Route::post('/reset/{token}', [WebAuthController::class, 'PostReset']);

// Profile Routes
Route::get('/student/profile', [WebAuthController::class, 'profile'])->name('student.profile')->middleware('verified');
Route::post('/profile/update', [WebAuthController::class, 'update'])->name('profile.update')->middleware('verified');

// Notification Routes
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Admin Group Routes (middleware: admin)
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/admin/admin/list', [AdminController::class, 'list']);
    Route::get('/admin/admin/add', [AdminController::class, 'add']);
    Route::post('/admin/admin/add', [AdminController::class, 'insert'])->name('insert');

    // Student Routes
    Route::get('/admin/student/list', [StudentListController::class, 'studentList'])->name('student.list');
    Route::get('/admin/student/add', [StudentListController::class, 'add']);
    Route::get('/admin/student/edit/{id}', [StudentListController::class, 'edit'])->name('student.edit');
    Route::post('/admin/student/edit/{id}', [StudentListController::class, 'update'])->name('student.update');
    Route::get('/admin/student/delete/{id}', [StudentListController::class, 'delete']);
    Route::post('/admin/student/add', [StudentListController::class, 'insert'])->name('student.insert');

    // Project Routes
    Route::get('admin/project/list', [ProjectController::class, 'adminProjectList']);
    Route::get('admin/project/project/add', [ProjectController::class, 'add']);
    Route::post('admin/ajax_get_subject', [ProjectController::class, 'ajax_get_subject']);
    Route::post('admin/project/project/add', [ProjectController::class, 'insert']);
    Route::get('admin/project/project/edit/{id}', [ProjectController::class, 'edit']);
    Route::post('admin/project/project/edit/{id}', [ProjectController::class, 'update']);
    Route::delete('admin/project/project/delete/{id}', [ProjectController::class, 'delete']);
});

// Student Group Routes (middleware: student)
Route::group(['middleware' => ['auth', 'student']], function () {
    Route::get('/student/dashboard', [DashboardController::class, 'dashboard'])->name('userdashboard');

    // Project Routes
    Route::get('student/project/list', [ProjectController::class, 'project']);
    Route::get('student/project/project/add', [ProjectController::class, 'add']);
    Route::post('student/ajax_get_subject', [ProjectController::class, 'ajax_get_subject']);
    Route::post('student/project/project/add', [ProjectController::class, 'insert']);
    Route::get('student/project/project/edit/{id}', [ProjectController::class, 'edit']);
    Route::post('student/project/project/edit/{id}', [ProjectController::class, 'update']);
    Route::post('student/project/project/delete/{id}', [ProjectController::class, 'delete']);
    Route::post('/invite/{projectId}', [ProjectController::class, 'invite'])->name('invite');
    Route::post('/task/submit/{id}', [ProjectController::class, 'tasksubmit'])->name('task.submit');
    Route::get('/student/project/view/{projectId}', [ProjectController::class, 'viewTasks'])->name('project.view.tasks');
    Route::post('/student/project/view/{projectId}/task/{taskId}/done', [ProjectController::class, 'markTaskAsDone'])->name('done.task');

    // Project Invitation Routes
    Route::post('/projects/{project}/invite', [ProjectInvitationController::class, 'invite'])->name('projects.invite');
    Route::get('/projects/{project}/accept-invitation', [ProjectInvitationController::class, 'acceptInvitation'])->name('projects.acceptInvitation');
    Route::post('/projects/{project}/reject-invitation', [ProjectInvitationController::class, 'rejectInvitation'])->name('projects.rejectInvitation');
});

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email verified successfully. Please log in.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
