<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'nullable|json',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->is_featured = $request->has('is_featured');
        $product->is_active = true;

        // Handle sizes
        if ($request->filled('sizes')) {
            $product->sizes = json_decode($request->sizes, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $product->image = 'products/' . $filename;
        }

        $product->save();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'nullable|json',
        ]);

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->is_featured = $request->has('is_featured');

        // Handle sizes
        if ($request->filled('sizes')) {
            $product->sizes = json_decode($request->sizes, true);
        }

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $product->image = 'products/' . $filename;
        }

        $product->save();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Xóa sản phẩm thành công!');
    }

    public function export()
    {
        return Excel::download(new ProductsExport(), 'products-' . now()->format('Ymd_His') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new ProductsImport();
        Excel::import($import, $request->file('file'));

        if (! empty($import->getErrors())) {
            return redirect()
                ->route('admin.products.index')
                ->with('warning', 'Da import ' . $import->getImportedCount() . ' san pham, co loi o mot so dong.')
                ->with('import_errors', $import->getErrors());
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Import thanh cong ' . $import->getImportedCount() . ' san pham.');
    }
}