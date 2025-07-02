@extends('layouts.app')

@section('title', 'Tambah Detail Pengembalian')

@section('content')
<div class="container mx-auto">
    <div class="page-intro">
        <h2 class="text-2xl font-bold mb-4">Tambah Detail Pengembalian</h2>
    </div>

    <div class="form-container">
        <form action="{{ route('borrow-details.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="return_request_id" value="{{ $returnRequest->id }}">

            <div class="mb-4">
                <label class="block mb-2">Pilih Unit Barang:</label>
                <select name="item_unit_id" class="w-full">
                    @foreach ($itemUnits as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->unit_code }} - {{ $unit->item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Kondisi Barang:</label>
                <input type="text" name="condition" class="w-full bg-gray-800 text-white p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Jumlah (opsional):</label>
                <input type="number" name="quantity" class="w-full bg-gray-800 text-white p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Foto Barang (opsional):</label>
                <input type="file" name="photo" accept="image/*" class="w-full">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            <a href="{{ route('return-requests.show', $returnRequest->id) }}" class="text-gray-400 ml-4 hover:underline">Kembali</a>
        </form>
    </div>
</div>
@endsection
