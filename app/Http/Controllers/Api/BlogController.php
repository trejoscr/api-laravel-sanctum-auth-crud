<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
  
  public function createBlog(Request $request)
  {
    $request->validate([
      'title'   => 'required',
      'content' => 'required',
    ]);

    $user_id = auth()->user()->id;

    $blog = new Blog();
    $blog->user_id = $user_id;
    $blog->title   = $request->title;
    $blog->content = $request->content;
    $blog->save();

    return response()->json([
      'status' => 1,
      'msg'    => 'Blog created successfully!',
    ], 200);

  }

  public function listBlog()
  {
    $user_id = auth()->user()->id;
    $blogs = Blog::where('user_id', $user_id)->get();

    return response()->json([
      'status' => 1,
      'msg'    => 'List Blogs',
      'data'   => $blogs
    ], 200);
  }

  public function showBlog($id)
  {
    $user_id = auth()->user()->id;

    if (Blog::where(['user_id'=>$user_id,'id'=>$id])->exists()) {

      $blog = Blog::where(['user_id'=>$user_id,'id'=>$id])->first();

      return response()->json([
        'status' => 1,
        'msg'    => 'Single Post',
        'data'   => $blog
      ], 200);

    }else{

      return response()->json([
        'status' => 0,
        'msg'    => 'Blog not found!',
      ], 404);

    }

  }

  public function updateBlog(Request $request, $id)
  {
    $request->validate([
      'title'   => 'required',
      'content' => 'required',
    ]);

    $user_id = auth()->user()->id;

    if (Blog::where(['user_id'=>$user_id,'id'=>$id])->exists()) {

      $blog = Blog::find($id);
      $blog->title   = $request->title;
      $blog->content = $request->content;
      $blog->save();

      return response()->json([
        'status' => 1,
        'msg'    => 'Blog updated successfully!',
      ], 200);

      
    }else{

      return response()->json([
        'status' => 0,
        'msg'    => 'Blog not found!',
      ], 404);

    }
  }

  public function deleteBlog($id)
  {
    $user_id = auth()->user()->id;

    if (Blog::where(['user_id'=>$user_id,'id'=>$id])->exists()) {

      $blog = Blog::where(['user_id'=>$user_id,'id'=>$id])->first();
      $blog->delete();

      return response()->json([
        'status' => 1,
        'msg'    => 'Blog deleted successfully!',
      ], 200);

      
    }else{

      return response()->json([
        'status' => 0,
        'msg'    => 'Blog not found!',
      ], 404);

    }
  }

}
