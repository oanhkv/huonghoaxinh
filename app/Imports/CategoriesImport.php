<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoriesImport implements ToCollection, WithHeadingRow
{
    private int $importedCount = 0;

    private array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $name = trim((string) ($row['name'] ?? ''));

            if ($name === '') {
                $this->errors[] = "Dong {$line}: thieu ten danh muc.";
                continue;
            }

            $parentName = trim((string) ($row['parent_name'] ?? ''));
            $parentId = null;

            if ($parentName !== '') {
                if (Str::lower($parentName) === Str::lower($name)) {
                    $this->errors[] = "Dong {$line}: parent_name khong duoc trung voi name.";
                    continue;
                }

                $parent = Category::where('name', $parentName)->first();

                if (! $parent) {
                    $this->errors[] = "Dong {$line}: khong tim thay danh muc cha '{$parentName}'.";
                    continue;
                }

                $parentId = $parent->id;
            }

            $slug = Str::slug($name);

            if ($slug === '') {
                $this->errors[] = "Dong {$line}: khong tao duoc slug tu ten danh muc.";
                continue;
            }

            $category = Category::firstOrNew(['slug' => $slug]);
            $category->name = $name;
            $category->description = $row['description'] ?? null;
            $category->parent_id = $parentId;
            $category->save();

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
}
