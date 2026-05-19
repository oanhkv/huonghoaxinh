<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::query()->with('category')->latest('id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $posts = $query->paginate(10)->withQueryString();

        // Categories ordered by post-count desc → tab "phổ biến nhất" lên đầu
        $categories = BlogCategory::withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->get();

        $stats = [
            'total'      => BlogPost::count(),
            'active'     => BlogPost::where('is_active', true)->count(),
            'inactive'   => BlogPost::where('is_active', false)->count(),
            'categories' => BlogCategory::count(),
        ];

        return view('admin.blog_posts.index', compact('posts', 'categories', 'stats'));
    }

    public function create()
    {
        return view('admin.blog_posts.form', [
            'post' => new BlogPost(['is_active' => true]),
            'categories' => BlogCategory::orderBy('name')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $validated['slug'] = $this->uniqueSlug($validated['title'], $validated['slug'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['published_at'] = $request->filled('published_at')
            ? $request->published_at
            : ($validated['is_active'] ? now() : null);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Đã tạo bài viết blog.');
    }

    public function edit(BlogPost $blog_post)
    {
        return view('admin.blog_posts.form', [
            'post' => $blog_post,
            'categories' => BlogCategory::orderBy('name')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, BlogPost $blog_post)
    {
        $validated = $this->validateRequest($request, $blog_post);

        $validated['slug'] = $this->uniqueSlug($validated['title'], $validated['slug'] ?? null, $blog_post->id);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['published_at'] = $request->filled('published_at')
            ? $request->published_at
            : ($blog_post->published_at ?: ($validated['is_active'] ? now() : null));

        if ($request->hasFile('image')) {
            if ($blog_post->image && str_starts_with($blog_post->image, 'blog/')) {
                Storage::disk('public')->delete($blog_post->image);
            }
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        $blog_post->update($validated);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(BlogPost $blog_post)
    {
        if ($blog_post->image && str_starts_with($blog_post->image, 'blog/')) {
            Storage::disk('public')->delete($blog_post->image);
        }
        $blog_post->delete();

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Đã xoá bài viết.');
    }

    // ----------------------- helpers -----------------------
    private function validateRequest(Request $request, ?BlogPost $post = null): array
    {
        return $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'image'            => 'nullable|image|max:4096',
            'published_at'     => 'nullable|date',
        ]);
    }

    private function uniqueSlug(string $title, ?string $candidate = null, ?int $ignoreId = null): string
    {
        $base = Str::slug(trim($candidate) !== '' ? $candidate : $title) ?: 'bai-viet';
        $slug = $base;
        $i = 1;
        while (BlogPost::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()) {
            $slug = $base.'-'.(++$i);
        }
        return $slug;
    }
}
