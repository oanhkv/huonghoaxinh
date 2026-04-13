<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    private int $importedCount = 0;

    private array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;

            $name = trim((string) ($row['name'] ?? ''));
            $categoryName = trim((string) ($row['category_name'] ?? ''));

            if ($name === '') {
                $this->errors[] = "Dong {$line}: thieu ten san pham.";
                continue;
            }

            if ($categoryName === '') {
                $this->errors[] = "Dong {$line}: thieu category_name.";
                continue;
            }

            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                $this->errors[] = "Dong {$line}: khong tim thay danh muc '{$categoryName}'.";
                continue;
            }

            $slug = Str::slug($name);

            if ($slug === '') {
                $this->errors[] = "Dong {$line}: khong tao duoc slug tu ten san pham.";
                continue;
            }

            $product = Product::firstOrNew(['slug' => $slug]);
            $product->name = $name;
            $product->description = $row['description'] ?? null;
            $product->price = (float) ($row['price'] ?? 0);
            $product->stock = (int) ($row['stock'] ?? 0);
            $product->category_id = $category->id;
            $product->is_featured = $this->toBool($row['is_featured'] ?? 0);
            $product->is_active = $this->toBool($row['is_active'] ?? 1);
            $product->save();

            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = Str::lower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'co', 'dang ban', 'noi bat'], true);
    }
}
