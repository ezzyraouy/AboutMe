<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/dashboard', function () {
//     return 'Admin Dashboard Works!';
// })->name('dashboard');

Route::middleware(['auth', 'is_admin'])->group(callback: function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('blogs', BlogController::class);
    Route::delete('/blogs/{blog}/remove-image', [BlogController::class, 'removeImage'])->name('blogs.removeImage');
    //projects
    Route::resource('projects', ProjectController::class);
    Route::delete('/projects/{project}/remove-image', [ProjectController::class, 'removeImage'])->name('projects.removeImage');
    //educations
    Route::resource('educations', EducationController::class);
    //experiences
    Route::resource('experiences', ExperienceController::class);
    //skills
    Route::resource('skills', SkillController::class);
    //services
    Route::resource('services', ServiceController::class);
    //certificates
    Route::resource('certificates', CertificateController::class);
    //categories
    Route::resource('categories', CategoryController::class);
    //slides
    Route::resource('slides', SlideController::class);
    Route::post('/slides/delete-file', [SlideController::class, 'deleteFile'])->name('slides.deletefile');

    Route::resource('users', UserController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('resources', ResourceController::class);
});
