<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'name' => 'required|string|unique:items,name',
            'type' => 'required|in:consumable,non-consumable',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'category' => 'required|exists:categories,name',
        ]);
        if ($validator->fails()) {
            throw new \Exception('Row validation failed: ' . json_encode($validator->errors()->all()));
        }
        $category_id = Category::where('name', $row['category'])->value('id');
        return new Item([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'] ?? null,
            'image' => $row['image'] ?? null,
            'category_id' => $category_id,
        ]);
    }
}
