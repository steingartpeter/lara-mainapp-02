<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/admins-only', function () {
  return 'You are an admin, lets get to work!';
})->middleware('can:visitAdminPages');


//<nn>
// USER related routes
//</nn>

//<nn>
// Here we can use the name (like an alias?) to redirect in case of missing login.  
//</nn>
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');

//<nn>
// Blog post related routes
//</nn>
// <nn> 
// This is the middleware to check if the user is logged in. 
// In case of: ->middleware('guest') we will be redirected to 'home'.
// </nn>
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

//<nn>
// Profile related routes
// Here we can set, that the username must be used instead of default id.
//</nn>
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('mustBeLoggedIn');
Route::get('manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);

//<nn>
// Followers related routes
//</nn>
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');