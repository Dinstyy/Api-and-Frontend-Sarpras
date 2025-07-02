@extends('layouts.app')

@section('title', 'Detail Return Request')

@section('content')
<div class="page-intro">
    <h2>Detail Permintaan Pengembalian #{{ $returnRequest->id }}</h2>
    <p>View details of the return request.</p>
</div>

<div class="details-container">
    <div class="bg-white p-6 rounded shadow mb-6">
        <p class="mb-2"><strong>Peminjam:</strong> {{ $returnRequest->user->name ?? 'Unknown' }}</p>
        <p class="mb-2"><strong>Tanggal Pengembalian:</strong> {{ $returnRequest->created_at->format('d/m/Y') }}</p>
        <p class="mb-2"><strong>Catatan:</strong> {{ $returnRequest->notes ?? '-' }}</p>
        <p class="mb-2"><strong>Status:</strong> {{ ucfirst($returnRequest->status) }}</p>
        @if ($returnRequest->status == 'rejected')
            <p class="mb-2"><strong>Alasan Penolakan:</strong> {{ $returnRequest->rejection_reason ?? '-' }}</p>
        @endif
        <p class="mb-2"><strong>Penanggung Jawab:</strong> {{ $returnRequest->handler ? $returnRequest->handler->name : '-' }}</p>
    </div>

    <h3 class="text-xl font-bold mb-4">Daftar Item Dikembalikan</h3>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Kuantitas</th>
                    <th>Kondisi</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returnRequest->returnDetails as $detail)
                    <tr>
                        <td>{{ $detail->itemUnit->item->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->condition }}</td>
                        <td>
                            @if ($detail->photo)
                                <img src="{{ asset('storage/' . $detail->photo) }}" alt="Return Photo" class="h-20">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
