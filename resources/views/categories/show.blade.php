@extends('layouts.app')

@section('title', 'Category Details')
@section('icon', 'bookmark')

@section('content')
    <h2 style="margin-bottom: 16px; font-size: 18px;">Detail Kategori</h2>
    <div style="background: #2c2c2c; padding: 16px; border-radius: 6px; width: fit-content;">
        <p><strong>Nama:</strong> {{ $category->name }}</p>
        <p><strong>Deskripsi:</strong> {{ $category->description ?? '-' }}</p>
        <p><strong>Slug:</strong> {{ $category->slug }}</p>
        <p><strong>Dibuat:</strong> {{ $category->created_at->format('d M Y H:i') }}</p>
        <p><strong>Diperbarui:</strong> {{ $category->updated_at->format('d M Y H:i') }}</p>
    </div>

    <div style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h2 style="font-size: 18px; margin-bottom: 8px;">Item dalam Kategori</h2>
                <p style="color: #888; font-size: 14px;">Total {{ $category->items->count() }} item terdaftar</p>
            </div>
            <a href="{{ route('items.create') }}?category={{ $category->slug }}" style="background: #4C6EF5; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">+ Tambah Item Baru</a>
        </div>

        @if ($category->items->isEmpty())
            <p style="color: #888;">Tidak ada item dalam kategori ini.</p>
        @else
            <div style="background: #f9f9f9; padding: 16px; border-radius: 6px;">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 10px; font-weight: 500; color: #666; padding-bottom: 8px; border-bottom: 1px solid #ddd;">
                    <span>Nama Item</span>
                    <span>Tipe</span>
                    <span>Dibuat</span>
                    <span>Aksi</span>
                </div>
                @foreach ($category->items as $item)
                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 10px; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if ($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" style="width: 24px; height: 24px; border-radius: 4px;">
                            @else
                                <span style="width: 24px; height: 24px; background: #ddd; display: inline-block; border-radius: 4px;"></span>
                            @endif
                            <span style="color: #333; font-size: 14px;">{{ $item->name }}</span>
                        </div>
                        <span style="color: #666; font-size: 14px; background: #e6f0fa; padding: 4px 8px; border-radius: 12px; display: inline-block; width: fit-content;">{{ $item->type }}</span>
                        <span style="color: #666; font-size: 14px;">{{ $item->created_at->diffForHumans() }}</span>
                        <div>
                            <a href="{{ route('items.showView', $item->id) }}" style="background: #f5c043; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; margin-right: 8px; font-size: 12px;">Lihat</a>
                            <a href="{{ route('items.edit', $item->id) }}" style="background: #4C6EF5; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px;">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <a href="{{ route('categories.edit', $category->slug) }}" style="display: inline-block; margin-top: 16px; background: #4C6EF5; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
        Edit Kategori
    </a>
@endsection
