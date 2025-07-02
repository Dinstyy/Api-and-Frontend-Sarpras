@extends('layouts.app')

@section('title', 'Warehouses')
@section('icon', 'box')

@section('content')
    <h2 style="margin-bottom: 16px; font-size: 18px;">Daftar Gudang</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 16px;">
        <div>
            <a href="{{ route('warehouses.create') }}" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
                + Tambah Gudang
            </a>
        </div>
        <div>
            <!-- Add export buttons if needed -->
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; color: white;" id="warehousesTable">
        <thead style="background-color: #722be0;">
            <tr>
                <th style="font-size: 15px; text-align: left; padding: 12px;">No</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Nama</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Lokasi</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Kapasitas</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="warehouse-table-body">
            @forelse ($warehouses as $index => $warehouse)
                <tr>
                    <td style="padding: 12px;">{{ $warehouses->firstItem() + $index }}</td>
                    <td style="padding: 12px;">{{ $warehouse->name }}</td>
                    <td style="padding: 12px;">{{ $warehouse->location }}</td>
                    <td style="padding: 12px;">{{ $warehouse->capacity }}</td>
                    <td style="padding: 12px; display: flex; gap: 8px;">
                        <a href="{{ route('warehouses.show', $warehouse->id) }}"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="eye" style="font-size: 16px;"></i>
                            Lihat
                        </a>
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="edit" style="font-size: 16px;"></i>
                            Edit
                        </a>
                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus gudang ini?')"
                                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                                <i data-feather="trash" style="font-size: 16px;"></i>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">Tidak ada gudang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;" id="pagination-links">
        {{ $warehouses->links() }}
    </div>
@endsection
