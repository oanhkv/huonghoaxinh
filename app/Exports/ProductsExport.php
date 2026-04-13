<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category')
            ->latest('id')
            ->get()
            ->map(function (Product $product) {
                return [
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category_name' => $product->category?->name,
                    'is_featured' => $product->is_featured ? 1 : 0,
                    'is_active' => $product->is_active ? 1 : 0,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'stock',
            'category_name',
            'is_featured',
            'is_active',
        ];
    }
}
