@extends('layouts.app')

@section('title', 'Edit Warehouse')
@section('icon', 'box')

@section('content')
    <h2 style="margin-bottom: 16px; font-size: 18px;">Edit Gudang</h2>
    <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom: 16px;">
            <label for="name" style="display: block; margin-bottom: 4px;">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $warehouse->name) }}" required style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; width: 300px;">
            @error('name')
                <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 16px;">
            <label for="location" style="display: block; margin-bottom: 4px;">Lokasi</label>
            <input type="text" id="location" name="location" value="{{ old('location', $warehouse->location) }}" required style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; width: 300px;">
            @error('location')
                <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 16px;">
            <label for="capacity" style="display: block; margin-bottom: 4px;">Kapasitas</label>
            <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $warehouse->capacity) }}" required style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; width: 300px;">
            @error('capacity')
                <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; border: none; font-size: 14px; font-weight: 500; cursor: pointer;">
            Simpan
        </button>
    </form>
@endsection
