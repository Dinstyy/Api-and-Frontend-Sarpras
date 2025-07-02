@extends('layouts.app')

@section('title', 'Edit Item Unit')
@section('icon', 'file-text')

@section('content')
    <div class="page-intro">
        <h2>Edit Item Unit: {{ $itemUnit->unit_code }}</h2>
        <p>Update the details of the item unit.</p>
    </div>

    @if (session('error'))
        <div style="color: #e57373; margin-bottom: 16px; background: #2c2c2c; padding: 10px; border-radius: 6px;">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div style="color: #4caf50; margin-bottom: 16px; background: #2c2c2c; padding: 10px; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="content">
        <div style="width: 100%; background: #111; padding: 24px; border-radius: 8px;">
            <form action="{{ route('item-units.update', $itemUnit->unit_code) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Unit Code</label>
                        <input type="text" name="unit_code" value="{{ old('unit_code', $itemUnit->unit_code) }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter unit code">
                        @error('unit_code')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Merk</label>
                        <input type="text" name="merk" value="{{ old('merk', $itemUnit->merk) }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter merk">
                        @error('merk')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Condition</label>
                        <select name="condition" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select condition</option>
                            <option value="Good" {{ old('condition', $itemUnit->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Broken" {{ old('condition', $itemUnit->condition) == 'Broken' ? 'selected' : '' }}>Broken</option>
                            <option value="Needs Improvement" {{ old('condition', $itemUnit->condition) == 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement</option>
                        </select>
                        @error('condition')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Status</label>
                        <select name="status" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select status</option>
                            <option value="available" {{ old('status', $itemUnit->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="borrowed" {{ old('status', $itemUnit->status) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="unknown" {{ old('status', $itemUnit->status) == 'unknown' ? 'selected' : '' }}>Unknown</option>
                            <option value="unavailable" {{ old('status', $itemUnit->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                        @error('status')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Dari</label>
                        <input type="text" name="diperoleh_dari" value="{{ old('diperoleh_dari', $itemUnit->diperoleh_dari) }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter source">
                        @error('diperoleh_dari')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Tanggal</label>
                        <input type="date" name="diperoleh_tanggal" value="{{ old('diperoleh_tanggal', $itemUnit->diperoleh_tanggal) }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        @error('diperoleh_tanggal')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $itemUnit->quantity) }}" min="1" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter quantity">
                        @error('quantity')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Item</label>
                        <select name="item_id" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id', $itemUnit->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Warehouse</label>
                        <select name="warehouse_id" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $itemUnit->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Current Location</label>
                        <input type="text" name="current_location" value="{{ old('current_location', $itemUnit->current_location) }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter current location">
                        @error('current_location')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Notes</label>
                        <textarea name="notes" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter notes">{{ old('notes', $itemUnit->notes) }}</textarea>
                        @error('notes')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div style="margin-top: 24px;">
                    <button type="submit" style="background: #722be0; color: white; padding: 10px 20px; border-radius: 6px; border: none; font-size: 14px; cursor: pointer;">
                        <i data-feather="save"></i> Update Item Unit
                    </button>
                    <a href="{{ route('item-units.viewIndex') }}" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-left: 10px;">
                        <i data-feather="x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
