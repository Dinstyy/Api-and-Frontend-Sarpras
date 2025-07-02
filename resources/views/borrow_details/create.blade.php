@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Item ke Peminjaman #{{ $borrowRequest->id }}</h1>

    <form action="{{ route('borrow-details.store') }}" method="POST" class="max-w-lg">
        @csrf
        <input type="hidden" name="borrow_request_id" value="{{ $borrowRequest->id }}">
        <div class="mb-4">
            <label class="block text-sm font-medium">Item</label>
            <select name="item_unit_id" class="w-full border rounded px-3 py-2">
                <option value="">Pilih Item</option>
                @foreach ($itemUnits as $unit)
                <option value="{{ $unit->id }}">{{ $unit->item->name }} ({{ $unit->quantity }} tersedia)</option>
                @endforeach
            </select>
            @error('item_unit_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Kuantitas</label>
            <input type="number" name="quantity" class="w-full border rounded px-3 py-2" value="{{ old('quantity') }}" min="1">
            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>
</div>
@endsection
