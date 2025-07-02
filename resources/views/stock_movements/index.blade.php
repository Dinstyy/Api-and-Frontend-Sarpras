@extends('layouts.app')

@section('title', 'Stock Manage')

@section('content')
<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-overlay::before {
    content: '';
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    background: rgba(0,0,0,0.2); /* optional gelapin */
    z-index: -1;
}

    .modal-form {
        background-color: #1a1a1a;
        padding: 24px;
        border-radius: 10px;
        width: 500px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

    <div id="modalWrapper"></div>

<div id="pageContent" style="position: relative;">
    <div class="page-intro">
        <h2>Stock Movements</h2>
        <p>Daftar pergerakan stok berdasarkan unit barang</p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <form method="GET" class="filter-form">
            <input type="text" name="search" placeholder="Cari nama item..." value="{{ request('search') }}">
            <select name="type">
                <option value="">Semua Tipe</option>
                <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
                <option value="damaged" {{ request('type') === 'damaged' ? 'selected' : '' }}>Rusak</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}">
            <select name="sort">
                <option value="item_asc" {{ request('sort') === 'item_asc' ? 'selected' : '' }}>Nama Item A-Z</option>
                <option value="item_desc" {{ request('sort') === 'item_desc' ? 'selected' : '' }}>Nama Item Z-A</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <button onclick="openModal()" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); color: white; padding: 10px 20px; border-radius: 6px; font-weight: 500;">
            + Tambah Movement
        </button>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($movement->movement_date)->format('Y-m-d H:i') }}</td>
                        <td>{{ $movement->itemUnit->item->name ?? '-' }}</td>
                        <td>{{ $movement->itemUnit->unit_code ?? '-' }}</td>
                        <td>{{ ucfirst($movement->type) }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada data movement ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $movements->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
function openModal() {
    const wrapper = document.getElementById('modalWrapper');

    wrapper.innerHTML = `
        <div class="modal-overlay" onclick="closeModal(event)">
            <form action="{{ route('stock_movements.store') }}" method="POST" class="modal-form" onclick="event.stopPropagation()">
                @csrf
                <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px;">Tambah Stock Movement</h3>
                <p style="color: #bbb; font-size: 13px; margin-bottom: 16px;">Lengkapi form berikut untuk menambahkan stock movement</p>

                <div style="margin-bottom: 14px;">
                    <label>Item Unit <span style="color: red">*</span></label>
                    <select name="item_unit_id" required style="width: 100%;">
                        <option value="">-- Pilih Item Unit --</option>
                        @foreach ($itemUnits as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_code }} - {{ $unit->item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 14px;">
                    <label>Jenis Movement <span style="color: red">*</span></label>
                    <select name="type" required style="width: 100%;">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="in">Masuk</option>
                        <option value="out">Keluar</option>
                        <option value="damaged">Rusak</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; margin-bottom: 14px;">
                    <div style="flex: 1;">
                        <label>Quantity <span style="color: red">*</span></label>
                        <input type="number" name="quantity" min="1" value="1" required style="width: 100%;">
                    </div>
                    <div style="flex: 1;">
                        <label>Tanggal <span style="color: red">*</span></label>
                        <input type="datetime-local" name="movement_date" required style="width: 100%;">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="2" style="width: 100%;" placeholder="Masukkan deskripsi movement (opsional)"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" onclick="closeModal()" style="padding: 8px 14px; background-color: #333; color: white; border-radius: 6px;">Batal</button>
                    <button type="submit" style="padding: 8px 16px; background: linear-gradient(to right, #7c3aed, #9333ea); color: white; border-radius: 6px;">Simpan Movement</button>
                </div>
            </form>
        </div>
        `;
    }

    function closeModal() {
        document.getElementById('modalWrapper').innerHTML = '';
    }
</script>
@endsection
