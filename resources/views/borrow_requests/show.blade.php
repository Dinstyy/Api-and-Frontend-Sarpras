@extends('layouts.app')

@section('title', 'Detail Borrow Request')

@section('content')
<div class="page-intro">
    <h2>Detail Permintaan Peminjaman #{{ $borrowRequest->id }}</h2>
    <p>View details of the borrow request.</p>
</div>

<div class="details-container">
    <div class="bg-white p-6 rounded shadow mb-6">
        <p class="mb-2"><strong>Peminjam:</strong> {{ $borrowRequest->user->name ?? 'Unknown' }}</p>
        <p class="mb-2"><strong>Tanggal Pinjam:</strong> {{ $borrowRequest->borrow_date_expected->format('d/m/Y') }}</p>
        <p class="mb-2"><strong>Tanggal Kembali:</strong> {{ $borrowRequest->return_date_expected->format('d/m/Y') }}</p>
        <p class="mb-2"><strong>Alasan:</strong> {{ $borrowRequest->reason }}</p>
        <p class="mb-2"><strong>Catatan:</strong> {{ $borrowRequest->notes ?? '-' }}</p>
        <p class="mb-2"><strong>Status:</strong> {{ ucfirst($borrowRequest->status) }}</p>
        @if ($borrowRequest->status == 'rejected')
            <p class="mb-2"><strong>Alasan Penolakan:</strong> {{ $borrowRequest->rejection_reason ?? '-' }}</p>
        @endif
        <p class="mb-2"><strong>Penanggung Jawab:</strong> {{ $borrowRequest->handler ? $borrowRequest->handler->name : '-' }}</p>
    </div>

    <h3 class="text-xl font-bold mb-4">Daftar Item Dipinjam</h3>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Kuantitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowRequest->borrowDetails as $detail)
                    <tr>
                        <td>{{ $detail->itemUnit->item->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
