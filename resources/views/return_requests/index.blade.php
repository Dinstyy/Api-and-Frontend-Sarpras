@extends('layouts.app')

@section('title', 'Return Requests')

@section('content')
<div class="page-intro">
    <h2>Daftar Permintaan Pengembalian</h2>
    <p>Manage all return requests here.</p>
</div>

<div class="table-container">
    <div class="filter-form">
        <form class="flex flex-wrap gap-4">
            <input type="text" name="search" placeholder="Cari nama/kode..." value="{{ request('search') }}" class="search-input">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded px-3 py-2">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded px-3 py-2">
            <button type="submit" class="filter-form button">Filter</button>
        </form>
    </div>

    <div class="action-buttons mb-4 flex gap-4">
        <a href="{{ route('return-requests.exportExcel') }}" class="bg-green-500 text-white px-4 py-2 rounded">Export Excel</a>
        <a href="{{ route('return-requests.exportPdf') }}" class="bg-red-500 text-white px-4 py-2 rounded">Export PDF</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr>
                        <td>{{ $request->user->name ?? 'Unknown' }}</td>
                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td class="action-buttons">
                            <a href="{{ route('return-requests.show', $request->id) }}" class="text-blue-500 hover:underline">Detail</a>
                            @if ($request->status == 'pending')
                                <form action="{{ route('return-requests.approve', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-500 hover:underline">Setujui</button>
                                </form>
                                <form action="{{ route('return-requests.reject', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="rejection_reason" placeholder="Alasan penolakan" class="border rounded px-2 py-1" required>
                                    <button type="submit" class="text-red-500 hover:underline">Tolak</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $requests->links() }}</div>
</div>
@endsection
