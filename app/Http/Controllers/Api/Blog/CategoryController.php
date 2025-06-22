<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::with('parentCategory')->get();
        return response()->json(['data' => $categories]);
    }

    public function show($id)
    {
        $category = BlogCategory::with('parentCategory')->findOrFail($id);
        return response()->json(['data' => $category]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
        ]);

        $category = BlogCategory::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Категорію створено успішно.',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Категорію оновлено успішно!',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Категорію видалено успішно.']);
    }
}

