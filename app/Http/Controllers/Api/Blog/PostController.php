<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class PostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();
        return response()->json(['data' => $posts]);
    }

    public function show($id)
    {
        $post = BlogPost::with(['user', 'category'])->findOrFail($id);
        return response()->json(['data' => $post]);
    }

}
