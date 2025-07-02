@extends('layouts.app')

@section('title', 'View Item Unit')
@section('icon', 'file-text')

@section('content')
    <div class="page-intro">
        <h2>Item Unit: {{ $itemUnit->unit_code }}</h2>
        <p>Details of the selected item unit.</p>
    </div>

    <div class="content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Item Information Card -->
            <div class="lg:col-span-2">
                <div style="width: 100%; background: #111; padding: 24px; border-radius: 8px; border: 1px solid #2c2c2c;">
                    <h2 class="text-lg font-semibold text-gray-300 flex items-center mb-4">
                        <i data-feather="info" class="mr-2"></i> Informasi Item
                    </h2>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Unit Code</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->unit_code }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Merk</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->merk }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Condition</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->condition }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Status</label>
                            <p style="font-size: 14px; color: #ddd;">{{ ucfirst($itemUnit->status) }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Dari</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->diperoleh_dari }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Tanggal</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->diperoleh_tanggal }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Quantity</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->quantity }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Item</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->item->name }} ({{ $itemUnit->item->category->name }})</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Warehouse</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->warehouse->name }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Current Location</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->current_location ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Notes</label>
                            <p style="font-size: 14px; color: #ddd;">{{ $itemUnit->notes ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div>
                <div style="background: #111; padding: 24px; border-radius: 8px; border: 1px solid #2c2c2c;">
                    <h2 class="text-lg font-semibold text-gray-300 flex items-center mb-4">
                        <i data-feather="qrcode" class="mr-2"></i> QR Code
                    </h2>
                    <div class="flex flex-col items-center">
                        @if($itemUnit->qr_image)
                                @php
                                    $qrText =
                                        "Unit Code: {$itemUnit->unit_code}\n" .
                                        "Item: {$itemUnit->item->name}\n" .
                                        "Category: " . ($itemUnit->item->category->name ?? 'N/A') . "\n" .
                                        "Merk: {$itemUnit->merk}\n" .
                                        "Condition: {$itemUnit->condition}\n" .
                                        "Warehouse: " . ($itemUnit->warehouse->name ?? 'N/A') . "\n" .
                                        "Status: " . ucfirst($itemUnit->status) . "\n" .
                                        "Quantity: {$itemUnit->quantity}\n" .
                                        "Current Location: " . ($itemUnit->current_location ?? 'N/A');
                                @endphp

                                <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                                    @if ($itemUnit->qr_image && file_exists(public_path('storage/' . $itemUnit->qr_image)))
                                        <img src="{{ asset('storage/' . $itemUnit->qr_image) }}" alt="QR {{ $itemUnit->unit_code }}" style="width: 150px; height: 150px;">
                                    @else
                                        <p style="font-size: 14px; color: #e57373; margin-bottom: 16px;">QR code tidak tersedia</p>
                                    @endif
                                </div>
                        @else
                            <p style="font-size: 14px; color: #e57373; margin-bottom: 16px;">QR code not available</p>
                        @endif
                        <p style="font-size: 14px; color: #ccc; margin-bottom: 16px;">Scan QR code untuk verifikasi item</p>
                        <div class="flex gap-3 w-full">
                            <button onclick="window.print()" style="flex: 1; background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px;">
                                <i data-feather="printer"></i> Cetak
                            </button>
                            <a href="{{ route('item-units.downloadQr', $itemUnit->unit_code) }}" style="flex: 1; background: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                                <i data-feather="download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <a href="{{ route('item-units.edit', $itemUnit->unit_code) }}" style="background: #F0F4FF; color: #4C6EF5; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                <i data-feather="edit"></i> Edit
            </a>
            <a href="{{ route('item-units.viewIndex') }}" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-left: 10px;">
                <i data-feather="arrow-left"></i> Back to List
            </a>
        </div>
    </div>
@endsection
