@extends('layouts.app')
@section('title', 'Buat Pengembalian')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="page-intro">
        <h2 class="text-2xl font-bold mb-4">Pengembalian untuk #{{ $borrowRequest->id }}</h2>
    </div>

    <form action="{{ route('return-requests.store') }}" method="POST" enctype="multipart/form-data" class="form-container">
        @csrf

        <input type="hidden" name="borrow_request_id" value="{{ $borrowRequest->id }}">

        <div class="mb-4">
            <label for="notes">Catatan (opsional):</label>
            <textarea name="notes" class="w-full bg-gray-800 text-white p-2 rounded mt-1">{{ old('notes') }}</textarea>
        </div>

        <h3 class="text-lg font-bold mb-2">Item Dipinjam:</h3>
        @foreach ($borrowRequest->borrowDetail as $i => $detail)
        <div class="item-row">
            <input type="hidden" name="item_units[{{ $i }}][id]" value="{{ $detail->itemUnit->id }}">
            <label>{{ $detail->itemUnit->item->name }}</label>
            <input type="number" name="item_units[{{ $i }}][quantity]" placeholder="Jumlah dikembalikan" required>
            <input type="text" name="item_units[{{ $i }}][condition]" placeholder="Kondisi" required>
            <input type="file" name="item_units[{{ $i }}][photo]" accept="image/*" required>
        </div>
        @endforeach

        <button type="submit" class="mt-4 bg-purple-600 px-4 py-2 rounded text-white">Kirim Pengembalian</button>
    </form>
</div>
@endsection
