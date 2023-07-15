<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

  public function login(Request $request)
  {
    //<SF>
    // CREATED ON: 2023-06-30 <br>
    // CREATED BY: AX07057<br>
    // Short description of the function....<br>
    // PARAMETERS:
    //×-
    // @-- $request = the Request object instance -@
    //-×
    //CHANGES:
    //×-
    // @-- ... -@
    //-×
    //</SF>

    //<nn>
    // Validate the incoming fields
    //</nn>
    $incomingFields = $request->validate([
      'loginusername' => 'required',
      'loginpassword' => 'required',
    ]);

    //<nn>
    // Attempt to log the user in
    //</nn>
    if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
      $request->session()->regenerate();
      return redirect('/')->with('success', 'You\'ve successfully logged in');
      //return 'Login successful!!!';
    } else {
      return redirect('/')->with('failure', 'Invalid login credentials');
    }
  }

  public function register(Request $request)
  {
    //<SF>
    // CREATED ON: 2023-06-30 <br>
    // CREATED BY: AX07057<br>
    // Create a new user based on register data.<br>
    // PARAMETERS:
    //×-
    // @-- $request = the incoming request object -@
    //-×
    //CHANGES:
    //×-
    // @-- ... -@
    //-×
    //</SF>

    //<nn>
    // Validate the incoming fields
    //</nn>
    $incomingFields = $request->validate([
      'username' => ['required', 'min:3', 'max:30', Rule::unique('users', 'username')],
      'email' => ['required', 'email', Rule::unique('users', 'email')],
      'password' => ['required', 'min:8', 'confirmed'],
    ]);

    //<nn>
    // Hash the password
    //</nn>
    $incomingFields['password'] = bcrypt($incomingFields['password']);

    //<nn>
    // Create the new user, and save it to local variable
    //</nn>
    $usr = User::create($incomingFields);

    //<nn>
    // Login the user
    //</nn>
    auth()->login($usr);

    return redirect('/')->with('success', 'Your account successfully registered!');
  }

  public function showCorrectHomepage()
  {
    //<SF>
    // CREATED ON: 2023-06-30 <br>
    // CREATED BY: AX07057<br>
    // Show the correct view, based on AUTH status.<br>
    // PARAMETERS:
    //×-
    // @-- @param = ... -@
    //-×
    //CHANGES:
    //×-
    // @-- ... -@
    //-×
    //</SF>
    if (auth()->check()) {
      return view('homepage-feed');
    } else {
      return view('homepage');
    }
  }

  public function logout(Request $request)
  {
    //<SF>
    // CREATED ON: 2023-06-30 <br>
    // CREATED BY: AX07057<br>
    // Handle user logout.<br>
    // PARAMETERS:
    //×-
    // @-- @param = ... -@
    //-×
    //CHANGES:
    //×-
    // @-- ... -@
    //-×
    //</SF>
    auth()->logout();
    return redirect('/')->with('success', 'You are now logged out');
  }

  public function profile(User $user)
  {
    //<SF>
    // CREATED ON: 2023-06-30 <br>
    // CREATED BY: AX07057<br>
    // Show the profile page of the user.<br>
    // PARAMETERS:
    //×-
    // @-- $usr = USER instance based on usewrname property -@
    //-×
    //CHANGES:
    //×-
    // @-- ... -@
    //-×
    //</SF>
    $this->getSharedData($user);
    return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
  }

  public function showAvatarForm()
  {
    return view('avatar-form');
  }

  public function storeAvatar(Request $request)
  {
    //<nn>
    // Validate uploaded file
    //</nn>
    $request->validate([
      'avatar' => 'required|image|max:3000'
    ]);

    //<nn>
    // Create a filename
    //</nn>
    $user = auth()->user();
    $fileName = $user->id . '-' . uniqid() . '.jpg';

    //<nn>
    // Resize, and convert to jpg the uploaded file.
    //</nn>
    $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
    Storage::put('public/avatars/' . $fileName, $imgData);

    $oldAvatar = $user->avatar;

    //<nn>
    // Save users avatars filename
    //</nn>
    $user->avatar = $fileName;
    $user->save();

    //<nn>
    // Delete the old avatar, if there was a special one, not dummy was used
    //</nn>
    if ($oldAvatar !== '/fallback-avatar.jpg') {
      Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
    }

    //<nn>
    // Return to page with success message
    //</nn>
    return back()->with('success', 'You avatar is updated succesfully!');
  }

  public function profileFollowers(User $user)
  {
    $this->getSharedData($user);
    //return $user->followers()->latest()->get();
    return view('profile-followers', [
      'usrNm' => $user->username,
      'followers' => $user->followers()->latest()->get(),
    ]);
  }

  public function profileFollowing(User $user)
  {
    $this->getSharedData($user);
    return view('profile-following', [
      'following' => $user->followingTheseUsers()->latest()->get()
    ]);
  }

  private function getSharedData(User $user)
  {
    $currentlyFollowing = 0;

    if (auth()->check()) {
      $currentlyFollowing = Follow::where([
        ['user_id', '=', auth()->user()->id],
        ['followeduser', '=', $user->id]
      ])->count();
    }

    $thePosts = $user->posts()->latest()->get();
    View::share('sharedData', [
      'usrNm' => $user->username, 'posts' => $thePosts,
      'postCount' => $thePosts->count(), 'avatar' => $user->avatar, 'currentlyFollowing' => $currentlyFollowing
    ]);
  }
}
