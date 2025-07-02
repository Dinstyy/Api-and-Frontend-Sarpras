@extends('layouts.app')

@section('title', 'Warehouse Details')
@section('icon', 'box')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <a href="{{ route('warehouses.viewIndex') }}" style="text-decoration: none; color: #4C6EF5; font-size: 14px;">
                <i data-feather="arrow-left"></i> Kembali
            </a>
        </div>
        <div>
            <a href="{{ route('warehouses.edit', $warehouse->id) }}" style="margin-right: 8px; background: #4CAF50; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">
                Edit
            </a>
            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus gudang ini?')" style="background: #E03131; color: white; padding: 8px 14px; border: none; border-radius: 6px; font-weight: bold; font-size: 14px; cursor: pointer;">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <h2 style="margin-bottom: 16px; font-size: 18px; color: white;">Gudang: {{ $warehouse->name }}</h2>
    <div style="background: #2c2c2c; padding: 16px; border-radius: 6px; margin-bottom: 16px; display: flex; gap: 16px; border: 1px solid #333;">
        <div>
            <p style="color: #ccc;"><strong>Lokasi:</strong> {{ $warehouse->location }}</p>
            <p style="color: #ccc;"><strong>Total Item:</strong> {{ $warehouse->itemUnits->count() }}</p>
        </div>
        <div style="flex-grow: 1;">
            <div style="display: flex; gap: 16px;">
                <div style="flex: 1;">
                    <p style="color: #ccc;"><strong>Total Kapasitas</strong></p>
                    <p style="color: #fff;">{{ $warehouse->capacity }} unit</p>
                    <div style="width: 100%; background: #333; border-radius: 4px; height: 8px;">
                        <div style="width: {{ $capacityPercentage }}%; background: #4C6EF5; height: 100%; border-radius: 4px;"></div>
                    </div>
                    <p style="color: #ccc;">0% - 100%</p>
                </div>
                <div style="flex: 1;">
                    <p style="color: #ccc;"><strong>Kapasitas Terpakai</strong></p>
                    <p style="color: #fff;">{{ $usedCapacity }} unit ({{ number_format($capacityPercentage, 2) }}%)</p>
                    <div style="width: 100%; background: #333; border-radius: 4px; height: 8px;">
                        <div style="width: {{ $capacityPercentage }}%; background: #4C6EF5; height: 100%; border-radius: 4px;"></div>
                    </div>
                    <p style="color: #ccc;">0% - {{ number_format($capacityPercentage, 2) }}%</p>
                </div>
                <div style="flex: 1;">
                    <p style="color: #ccc;"><strong>Sisa Kapasitas</strong></p>
                    <p style="color: #fff;">{{ $remainingCapacity }} unit ({{ number_format($remainingPercentage, 2) }}%)</p>
                    <div style="width: 100%; background: #333; border-radius: 4px; height: 8px;">
                        <div style="width: {{ $remainingPercentage }}%; background: #10B981; height: 100%; border-radius: 4px;"></div>
                    </div>
                    <p style="color: #ccc;">0% - {{ number_format($remainingPercentage, 2) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div style="background: #2c2c2c; padding: 16px; border-radius: 6px; border: 1px solid #333;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; color: white; margin: 0;">Daftar Item di Gudang</h3>
            <a href="{{ route('item-units.create') }}" style="background: #4C6EF5; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px;">
                + Tambah Unit Baru
            </a>
        </div>
        <div style="margin-bottom: 16px;">
            <p style="color: #ccc;"><strong>Total Item Terdafar:</strong> {{ $warehouse->itemUnits->count() }}</p>
        </div>
        @forelse ($warehouse->itemUnits as $itemUnit)
            <div style="display: flex; align-items: center; padding: 12px; background: #333; border-radius: 6px; margin-bottom: 8px; border: 1px solid #444;">
                <div style="margin-right: 16px;">
                    @if($itemUnit->item->image)
                        <img src="{{ url($itemUnit->item->image) }}" alt="{{ $itemUnit->item->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    @else
                        <div style="width: 50px; height: 50px; background: #444; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #ccc;">No Image</div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <p style="margin: 0; font-weight: bold; color: white;">{{ $itemUnit->item->name }}</p>
                    <p style="margin: 4px 0 0; color: #ccc;">SKU: {{ $itemUnit->unit_code }}</p>
                    <p style="margin: 4px 0 0; color: #ccc;">{{ $itemUnit->quantity }} unit</p>
                </div>
                <div style="margin-left: 16px;">
                    <span style="background: #1a3c2a; color: #10B981; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                        {{ $itemUnit->status }}
                    </span>
                </div>
                <div style="margin-left: 16px;">
                    @php
                        $qrText = "Unit Code: {$itemUnit->unit_code}\n" .
                                  "Item: {$itemUnit->item->name}\n" .
                                  "Category: " . ($itemUnit->item->category->name ?? 'N/A') . "\n" .
                                  "Merk: " . ($itemUnit->merk ?? 'N/A') . "\n" .
                                  "Condition: " . ($itemUnit->condition ?? 'N/A') . "\n" .
                                  "Warehouse: " . ($itemUnit->warehouse->name ?? 'N/A') . "\n" .
                                  "Status: " . ucfirst($itemUnit->status) . "\n" .
                                  "Quantity: {$itemUnit->quantity}\n" .
                                  "Current Location: " . ($itemUnit->current_location ?? 'N/A');
                    @endphp
                                <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                                    @if ($itemUnit->qr_image && file_exists(public_path('storage/' . $itemUnit->qr_image)))
                                        <img src="{{ asset('storage/' . $itemUnit->qr_image) }}" alt="QR {{ $itemUnit->unit_code }}" style="width: 50px; height: 50px;">
                                    @else
                                        <p style="font-size: 14px; color: #e57373; margin-bottom: 16px;">QR code tidak tersedia</p>
                                    @endif
                                </div>
                </div>
                <div style="margin-left: 16px; display: flex; gap: 8px;">
                    <a href="{{ route('item-units.showView', $itemUnit->unit_code) }}" style="background: #2c2c2c; color: #D18616; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; border: 1px solid #444;">
                        Lihat
                    </a>
                    <a href="{{ route('item-units.edit', $itemUnit->unit_code) }}" style="background: #2c2c2c; color: #4C6EF5; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 12px; border: 1px solid #444;">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <p style="color: #ccc;">Tidak ada item di gudang ini.</p>
        @endforelse
    </div>
@endsection

@section('scripts')
    <script>
        feather.replace(); // Pastikan Feather Icons dirender
    </script>
@endsection
