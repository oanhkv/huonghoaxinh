<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogCategory::withCount('posts')->latest('id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog_categories.form', [
            'category' => new BlogCategory(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $validated['slug'] ?? null);

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Đã tạo danh mục blog.');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('admin.blog_categories.form', [
            'category' => $blog_category,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, BlogCategory $blog_category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name'], $validated['slug'] ?? null, $blog_category->id);

        $blog_category->update($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy(BlogCategory $blog_category)
    {
        if ($blog_category->posts()->exists()) {
            return back()->with('error', 'Không thể xoá danh mục đang có bài viết. Hãy chuyển bài sang danh mục khác trước.');
        }

        $blog_category->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Đã xoá danh mục.');
    }

    private function uniqueSlug(string $name, ?string $candidate = null, ?int $ignoreId = null): string
    {
        $base = Str::slug(trim($candidate) !== '' ? $candidate : $name) ?: 'danh-muc';
        $slug = $base;
        $i = 1;
        while (BlogCategory::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()) {
            $slug = $base.'-'.(++$i);
        }
        return $slug;
    }
}
