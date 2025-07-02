@extends('layouts.app')

@section('title', 'Edit Category')
@section('icon', 'bookmark')

@section('content')
 <h2 style="margin-bottom: 16px; font-size: 18px;">Edit Kategori</h2>
 <form action="{{ route('categories.update', $category->slug) }}" method="POST">
 @csrf
 @method('PUT')
 <div style="margin-bottom: 16px;">
 <label for="name" style="display: block; margin-bottom: 4px;">Nama</label>
 <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; width: 300px;">
 @error('name')
 <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
 @enderror
 </div>
 <div style="margin-bottom: 16px;">
 <label for="description" style="display: block; margin-bottom: 4px;">Deskripsi</label>
 <textarea id="description" name="description" style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; width: 300px; height: 100px;">{{ old('description', $category->description) }}</textarea>
 @error('description')
 <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
 @enderror
 </div>
 <button type="submit" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; border: none; font-size: 14px; font-weight: 500; cursor: pointer;">
 Simpan
 </button>
 </form>
@endsection
