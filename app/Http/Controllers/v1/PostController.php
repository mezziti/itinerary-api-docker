<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Traits\apiResponseTrait;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
  use apiResponseTrait;

  public function index()
  {
    $posts = Post::with('user')->orderBy('updated_at', 'desc')->get();
    return $this->apiResponse($posts, 200, 'ok');
  }


  public function store(StorePostRequest $request)
  {


    //$slug = Str::slug($request->title).'-'.bin2hex(random_bytes(5));

    if ($request->slug) {
      $slug = $request->slug;
    } else {
      $slug = Str::slug($request->title) . '-' . bin2hex(random_bytes(5));
    }

    try {
      $post = Post::create([
        'title' => $request->title,
        'slug' => $request->slug,
        'description' => $request->description,
        'user_id' => auth()->id(),
      ]);
      return $this->apiResponse($post, 201, 'Post created successfully');
    } catch (\Exception) {
      return $this->apiResponse(NULL, 404, 'cannot create the post');
    }
  }


  public function show(String $id)
  {
    try {
      $post = Post::with('user')->where('id', $id)->first();
      if ($post) {
        return $this->apiResponse($post, 200, 'ok');
      }
    } catch (\Exception) {
      return $this->apiResponse(NULL, 404, 'post not found');
    }
  }


  public function update(StorePostRequest $request, String $id)
  {
    $post = Post::with('user')->where('id', $id)->first();
    if (!$post) {
      return $this->apiResponse(NULL, 404, 'post not found');
    }
    if ($post->user_id !== auth()->id()) {
      return $this->apiResponse(NULL, 403, 'you are not allowed to update this post');
    }
    try {
      $post->update([
        'title' => $request->title,
        'slug' => $request->slug,
        'description' => $request->description,
      ]);
      return $this->apiResponse($post, 200, 'Post updated successfully');
    } catch (\Exception) {
      return $this->apiResponse(NULL,400,'cannot update the post');
    }
  }


  public function destroy(String $id)
  {
    $post = Post::with('user')->where('id', $id)->first();
    if (!$post) {
      return $this->apiResponse(NULL, 404, 'post not found');
    }
    if ($post->user_id !== auth()->id()) {
      return $this->apiResponse(NULL, 403, 'you are not allowed to delete this post');
    }
    try {
      $post->delete();
      return $this->apiResponse($post, 200, 'Post deleted successfully');
    } catch (\Exception) {
      return $this->apiResponse(NULL, 400, 'cannot delete the post');
    }
  }

}
