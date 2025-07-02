<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemUnitImport implements ToModel, WithHeadingRow
{
public function model(array $row)
{
    // Deteksi apakah nilai tanggal adalah serial Excel
    if (is_numeric($row['diperoleh_tanggal'])) {
        // Excel base date: 1900-01-01 + N days - 2 (karena bug Excel leap year)
        $diperolehTanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['diperoleh_tanggal']);
    } else {
        $diperolehTanggal = \DateTime::createFromFormat('Y-m-d', trim($row['diperoleh_tanggal']));
    }

    if ($diperolehTanggal === false) {
        throw new \Exception('Invalid date format for diperoleh_tanggal. Expected Y-m-d. Received: ' . $row['diperoleh_tanggal']);
    }

    $row['diperoleh_tanggal'] = $diperolehTanggal->format('Y-m-d');

    $validator = Validator::make($row, [
        'merk' => 'required|string',
        'condition' => 'required|in:Good,Broken,Needs Improvement',
        'notes' => 'nullable|string',
        'diperoleh_dari' => 'required|string',
        'diperoleh_tanggal' => 'required|date_format:Y-m-d',
        'status' => 'required|in:available,borrowed,unknown,unavailable',
        'quantity' => 'required|integer|min:1',
        'item_name' => 'required|exists:items,name',
        'warehouse_name' => 'required|exists:warehouses,name',
        'current_location' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        throw new \Exception('Row validation failed: ' . json_encode($validator->errors()->all()));
    }

    $item_id = Item::where('name', $row['item_name'])->value('id');
    $warehouse_id = Warehouse::where('name', $row['warehouse_name'])->value('id');
    $item = Item::find($item_id);

    // Enforce quantity = 1 for non-consumable items
    $quantity = $item->type === 'non-consumable' ? 1 : $row['quantity'];

    // Check warehouse capacity
    $warehouse = Warehouse::find($warehouse_id);
    if ($warehouse->capacity < $quantity) {
        throw new \Exception('Warehouse capacity insufficient for quantity: ' . $quantity);
    }

    DB::beginTransaction();
    try {
        // Generate unit_code
        $prefix = strtoupper(substr(preg_replace('/\s+/', '', $row['item_name']), 0, 20));
        $lastUnit = ItemUnit::where('unit_code', 'LIKE', $prefix . '%')
            ->orderBy('unit_code', 'desc')
            ->first();
        $newNumber = $lastUnit ? (int)substr($lastUnit->unit_code, -3) + 1 : 1;
        $unit_code = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Generate QR code
        $path = 'qr_codes/' . $unit_code . '.png';
        $fullPath = storage_path('app/public/' . $path);
        QrCode::size(300)->generate($unit_code, $fullPath);
        $qrImage = Storage::url($path);

        $itemUnit = new ItemUnit([
            'unit_code' => $unit_code,
            'merk' => $row['merk'],
            'condition' => $row['condition'],
            'notes' => $row['notes'] ?? null,
            'diperoleh_dari' => $row['diperoleh_dari'],
            'diperoleh_tanggal' => $row['diperoleh_tanggal'],
            'status' => $row['status'],
            'quantity' => $quantity,
            'qr_image' => $qrImage,
            'item_id' => $item_id,
            'warehouse_id' => $warehouse_id,
            'current_location' => $row['current_location'] ?? null,
        ]);

        $itemUnit->save();

        // Update warehouse capacity
        $warehouse->capacity -= $quantity;
        $warehouse->save();

        DB::commit();
        return $itemUnit;
    } catch (\Exception $e) {
        DB::rollBack();
        throw new \Exception('Failed to import item unit: ' . $e->getMessage());
    }
}
}
