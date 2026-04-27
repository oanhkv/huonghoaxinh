<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(10);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $colorOptions = Product::COLOR_OPTIONS;
        $materialOptions = Product::MATERIAL_OPTIONS;

        return view('admin.products.create', compact('categories', 'colorOptions', 'materialOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_name' => 'nullable|string|max:255',
            'sizes' => 'nullable|json',
            'colors' => 'nullable|array',
            'colors.*' => ['string', \Illuminate\Validation\Rule::in(array_keys(Product::COLOR_OPTIONS))],
            'materials' => 'nullable|array',
            'materials.*' => ['string', \Illuminate\Validation\Rule::in(Product::MATERIAL_OPTIONS)],
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->slug = $this->generateUniqueSlug((string) $request->name);
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

        $product->colors = $this->sanitizeList((array) $request->input('colors', []), array_keys(Product::COLOR_OPTIONS));
        $product->materials = $this->sanitizeList((array) $request->input('materials', []), Product::MATERIAL_OPTIONS);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $product->image = 'products/'.$filename;
        } elseif ($request->filled('image_name')) {
            $product->image = $this->normalizeImageName((string) $request->image_name);
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $colorOptions = Product::COLOR_OPTIONS;
        $materialOptions = Product::MATERIAL_OPTIONS;

        return view('admin.products.edit', compact('product', 'categories', 'colorOptions', 'materialOptions'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_name' => 'nullable|string|max:255',
            'sizes' => 'nullable|json',
            'colors' => 'nullable|array',
            'colors.*' => ['string', \Illuminate\Validation\Rule::in(array_keys(Product::COLOR_OPTIONS))],
            'materials' => 'nullable|array',
            'materials.*' => ['string', \Illuminate\Validation\Rule::in(Product::MATERIAL_OPTIONS)],
        ]);

        $product->name = $request->name;
        $product->slug = $this->generateUniqueSlug((string) $request->name, $product->id);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->is_featured = $request->has('is_featured');

        // Handle sizes
        if ($request->filled('sizes')) {
            $product->sizes = json_decode($request->sizes, true);
        }

        $product->colors = $this->sanitizeList((array) $request->input('colors', []), array_keys(Product::COLOR_OPTIONS));
        $product->materials = $this->sanitizeList((array) $request->input('materials', []), Product::MATERIAL_OPTIONS);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($product->image && str_starts_with(str_replace('\\', '/', $product->image), 'products/')) {
                Storage::delete('public/'.$product->image);
            }
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $product->image = 'products/'.$filename;
        } elseif ($request->filled('image_name')) {
            $product->image = $this->normalizeImageName((string) $request->image_name);
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && str_starts_with(str_replace('\\', '/', $product->image), 'products/')) {
            Storage::delete('public/'.$product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products-'.now()->format('Ymd_His').'.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new ProductsImport;
        Excel::import($import, $request->file('file'));

        if (! empty($import->getErrors())) {
            return redirect()
                ->route('admin.products.index')
                ->with('warning', 'Da import '.$import->getImportedCount().' san pham, co loi o mot so dong.')
                ->with('import_errors', $import->getErrors());
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Import thanh cong '.$import->getImportedCount().' san pham.');
    }

    private function normalizeImageName(string $imageName): string
    {
        $imageName = trim(str_replace('\\', '/', $imageName));
        if ($imageName === '') {
            return $imageName;
        }

        if (str_starts_with(strtolower($imageName), 'http://') || str_starts_with(strtolower($imageName), 'https://')) {
            return $imageName;
        }

        if (str_starts_with($imageName, '/')) {
            return ltrim($imageName, '/');
        }

        return $imageName;
    }

    private function sanitizeList(array $values, array $allowed): array
    {
        return array_values(array_unique(array_filter(
            $values,
            fn ($v) => is_string($v) && in_array($v, $allowed, true)
        )));
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'product';
        }

        $slug = $base;
        $i = 2;

        while (
            Product::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
