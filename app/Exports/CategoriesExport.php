<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Category::with('parent')
            ->latest('id')
            ->get()
            ->map(function (Category $category) {
                return [
                    'name' => $category->name,
                    'description' => $category->description,
                    'parent_name' => $category->parent?->name,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'parent_name',
        ];
    }
}
