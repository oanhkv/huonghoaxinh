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
        $posts = BlogPost::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $posts->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        $posts = $posts->orderByDesc('published_at')->paginate(9)->withQueryString();

        return view('frontend.blog.index', compact('posts', 'categories'));
    }

    public function show(BlogPost $post)
    {
        return view('frontend.blog.show', compact('post'));
    }
}
