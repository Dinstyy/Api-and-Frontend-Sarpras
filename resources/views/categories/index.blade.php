@extends('layouts.app')

@section('title', 'Categories')
@section('icon', 'bookmark')

@section('content')
    <h2 style="margin-bottom: 16px; font-size: 18px;">Daftar Kategori</h2>
    <div style="display: flex; justify-content: flex-start; align-items: center; width: 100%; margin-bottom: 16px;">
        <a href="{{ route('categories.create') }}" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
            + Tambah Kategori
        </a>
    </div>
    <table style="width: 100%; border-collapse: collapse; color: white;" id="categoriesTable">
        <thead style="background-color: #722be0;">
            <tr>
                <th style="font-size: 15px; text-align: left; padding: 12px;">No</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Nama</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Slug</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Deskripsi</th>
                <th style="font-size: 15px; text-align: left; padding: 12px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="category-table-body">
            @forelse ($categories as $index => $category)
                <tr>
                    <td style="padding: 12px;">{{ $categories->firstItem() + $index }}</td>
                    <td style="padding: 12px;">{{ $category->name }}</td>
                    <td style="padding: 12px;">{{ $category->slug }}</td>
                    <td style="padding: 12px;">{{ $category->description ?? '-' }}</td>
                    <td style="padding: 12px; display: flex; gap: 8px;">
                        <a href="{{ route('categories.show', $category->slug) }}"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="eye" style="font-size: 16px;"></i>
                            Lihat
                        </a>
                        <a href="{{ route('categories.edit', $category->slug) }}"
                           style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                            <i data-feather="edit" style="font-size: 16px;"></i>
                            Edit
                        </a>
                        <form action="{{ route('categories.destroy', $category->slug) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                                <i data-feather="trash" style="font-size: 16px;"></i>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">Tidak ada kategori ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;" id="pagination-links">
        {{ $categories->links() }}
    </div>
@endsection
