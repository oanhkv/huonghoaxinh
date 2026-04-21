<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();

        $featuredPost = BlogPost::with('category')
            ->where('is_active', true)
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', function ($query) use ($request) {
                    $query->where('slug', $request->category);
                });
            })
            ->orderByDesc('published_at')
            ->first();

        $posts = BlogPost::with('category')->where('is_active', true)
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', function ($query) use ($request) {
                    $query->where('slug', $request->category);
                });
            })
            ->when($featuredPost && ! $request->filled('category'), function ($q) use ($featuredPost) {
                $q->where('id', '!=', $featuredPost->id);
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    public function show(BlogPost $post)
    {
        return view('frontend.blog.show', compact('post'));
    }
}
