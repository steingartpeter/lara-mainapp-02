<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function showCreateForm()
  {
    //<nn>
    // Check if the user is logged in. If not, redirect to the home page.
    //</nn>
    // if (!auth()->check()) {
    //     return redirect('/');
    // }
    //<nn>
    // INSTEAD OF THIS ==> We can use the middleware to check if the user is logged in.
    //</nn>


    //<nn>
    // Return the standard blog creation view from template.
    //</nn>
    return view('create-post');
  }

  public function storeNewPost(Request $request)
  {
    //<nn>
    // Creat new blog post =>
    // 1. Validate the request data
    // 2. Store the new post in the database
    //</nn>
    $incmngFields = $request->validate([
      'title' => 'required|max:100',
      'body' => 'required'
    ]);


    //<nn>
    // Strip tags from the title and body fields to prevent XSS attacks.
    //</nn>
    $incmngFields['post_title'] = strip_tags($incmngFields['title']);
    $incmngFields['post_body'] = strip_tags($incmngFields['body']);
    $incmngFields['user_id'] = auth()->user()->id;


    $newPost = Post::create($incmngFields);

    //<nn>
    // After creation of the new post, redirect to the newly created post.
    //</nn>
    return redirect("/post/{$newPost->id}")->with('success', 'New post was created successfully!');
  }

  public function viewSinglePost(Post $post)
  {
    //<nn>
    // TYPE HINTING: Laravel will automatically query the database for the post with the given ID.
    //</nn>

    //<nn>
    // To see MARKDOWN, we need to ask Laravel, to convert markdown to HTML. 
    //</nn>
    $post['post_body'] = strip_tags(Str::markdown($post->post_body), '<p><ul><ol><li><strong><em><h1><h2><h3><h4><h5><h6><img><br><hr><blockquote><pre><table><thead><caption><tbody><tr><th><td>');

    //<nn>
    // Return the standard blog creation view from template.
    //</nn>
    return view('single-post', ['post' => $post]);
  }

  public function delete(Post $post)
  {
    // if (auth()->user()->cannot('delete', $post)) {
    //   return 'You can not delete the post...';
    // }

    //<nn>
    // Delete the post from the database.
    //</nn>
    $post->delete();

    //<nn>
    // After deletion of the post, redirect to the home page.
    //</nn>
    $url = '/profile/' . auth()->user()->username;
    return redirect($url)->with('success', 'Post was deleted successfully!');
  }


  public function actuallyUpdate(Post $post, Request $request)
  {
    //<nn>
    // Getr data from requesdt and validate it.
    //</nn>
    $incomingData = $request->validate([
      'title' => 'required|max:100',
      'body' => 'required'
    ]);

    //<nn>
    // Sanitize input data.
    //</nn>
    $incomingData['post_title'] = strip_tags($incomingData['title']);
    $incomingData['post_body'] = strip_tags($incomingData['body']);

    $post->update($incomingData);

    return back()->with('success', 'Post was updated successfully!');
  }

  public function showEditForm(Post $post)
  {
    return view('edit-post', ['post' => $post]);
  }

  public function search($term)
  {
    // We need composer to add SCOUT.
    // Then extent Post model with include Searchable, and with toSearchableArray() method.
    // Then we have to register SCOUT_DRIVER in .env file
    $posts = Post::search($term)->get();
    $posts->load('getUser:id,username,avatar');
    return $posts;
  }
}
