<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Всі пости
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();
        return response()->json(['data' => $posts]);
    }

    // Один пост
    public function show($id)
    {
        $post = BlogPost::with(['user', 'category'])->findOrFail($id);
        return response()->json(['data' => $post]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:2',
            'content_raw' => 'required|string|min:10',
            'category_id' => 'nullable|exists:blog_categories,id',
            'published_at' => 'nullable|date',
        ]);

        $post = BlogPost::create([
            'title' => $validated['title'],
            'content_raw' => $validated['content_raw'],
            'category_id' => $validated['category_id'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'user_id' => 2,
        ]);
        $post->load(['user', 'category']);
        return response()->json(['data' => $post], 201);
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|min:2',
            'content_raw' => 'required|string|min:10',
            'category_id' => 'nullable|exists:blog_categories,id',
            'published_at' => 'nullable|date',
        ]);

        $post->update($validated);

        return response()->json(['data' => $post]);
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Пост видалено']);
    }
}
