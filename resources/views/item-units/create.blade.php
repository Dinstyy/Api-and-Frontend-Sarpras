@extends('layouts.app')

@section('title', 'Create Item Unit')
@section('icon', 'file-text')

@section('content')
    <div class="page-intro">
        <h2>Create Item Unit</h2>
        <p>Add a new item unit to your inventory.</p>
    </div>

    <div class="content">
        <div style="width: 100%; background: #111; padding: 24px; border-radius: 8px;">
            <form action="{{ route('item-units.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Merk</label>
                        <input type="text" name="merk" value="{{ old('merk') }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter merk">
                        @error('merk')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Condition</label>
                        <select name="condition" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select condition</option>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Broken" {{ old('condition') == 'Broken' ? 'selected' : '' }}>Broken</option>
                            <option value="Needs Improvement" {{ old('condition') == 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement</option>
                        </select>
                        @error('condition')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Dari</label>
                        <input type="text" name="diperoleh_dari" value="{{ old('diperoleh_dari') }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter source">
                        @error('diperoleh_dari')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Diperoleh Tanggal</label>
                        <input type="date" name="diperoleh_tanggal" value="{{ old('diperoleh_tanggal') }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        @error('diperoleh_tanggal')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter quantity">
                        @error('quantity')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Item</label>
                        <select name="item_id" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                            <option value="">Select item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
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
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Current Location</label>
                        <input type="text" name="current_location" value="{{ old('current_location') }}" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter current location">
                        @error('current_location')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #ccc; margin-bottom: 8px; display: block;">Notes</label>
                        <textarea name="notes" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter notes">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div style="margin-top: 24px;">
                    <button type="submit" style="background: #722be0; color: white; padding: 10px 20px; border-radius: 6px; border: none; font-size: 14px; cursor: pointer;">
                        Create Item Unit
                    </button>
                    <a href="{{ route('item-units.viewIndex') }}" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-left: 10px;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
