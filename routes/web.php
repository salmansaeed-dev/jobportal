<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;

use App\Http\Controllers\Auth\PasswordResetController;


// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class , 'index'])->name('jobs');

Route::get('/jobs/details/{id}', [JobsController::class , 'details'])->name('jobDetails');

Route::post('/apply-Job', [JobsController::class , 'applyJob'])->name('applyJob');
Route::post('/save-Job', [JobsController::class , 'savejobs'])->name('savejobs');

// forgot paassword
  
Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
 



        Route::get('/account/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/account/process-register', [AccountController::class, 'registerProsess'])->name('account.Prosessregistration');
        Route::get('/account/login', [AccountController::class, 'login'])->name('login');
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');



        Route::middleware('auth')->group(function(){
            
            Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
            Route::put('/Update-profile', [AccountController::class, 'updatePropile'])->name('account.updatePropile');
            Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
            Route::post('/Update-Profile-Pic', [AccountController::class, 'updateprofliePic'])->name('account.updateprofliePic');
            Route::get('/create-job', [AccountController::class, 'createjob'])->name('account.createjob');
            Route::post('/save-job', [AccountController::class, 'savejobs'])->name('account.savejobs');
            Route::get('/my-jobs', [AccountController::class, 'myjobs'])->name('account.myjobs');
            Route::get('/my-jobs/edit/{Jobid}', [AccountController::class, 'editJob'])->name('account.editJob');
            Route::post('/update-job/{Jobid}', [AccountController::class, 'updatejobs'])->name('account.updatejobs');
            Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
            Route::get('/my-jobs-application', [AccountController::class, 'myjobsApplication'])->name('account.myjobsApplication');
            Route::post('/remove-job-application', [AccountController::class, 'removejobs'])->name('account.removejobs');
            Route::get('/saved-job', [AccountController::class, 'savedjobs'])->name('account.savedjobs');
            Route::post('/remove-save-job', [AccountController::class, 'removesavejob'])->name('account.removesavejob');
            Route::post('/update-password', [AccountController::class, 'updatepassword'])->name('account.updatepassword');
        });


        // Admin Route



