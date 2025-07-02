@extends('layouts.app')

@section('title', 'Edit Item')
@section('icon', 'file')

@section('content')
    <div class="page-intro">
        <h2>Edit Item</h2>
        <p>Update the details of the selected item.</p>
    </div>

    <div class="content">
        <div style="width: 100%; max-width: 600px; background: #111; padding: 24px; border-radius: 8px; border: 1px solid #2c2c2c;">
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;" placeholder="Enter item name">
                    @error('name')
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="type" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Type</label>
                    <select id="type" name="type" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        <option value="" disabled>Select item type</option>
                        <option value="consumable" {{ old('type', $item->type) === 'consumable' ? 'selected' : '' }}>Consumable</option>
                        <option value="non-consumable" {{ old('type', $item->type) === 'non-consumable' ? 'selected' : '' }}>Non-Consumable</option>
                    </select>
                    @error('type')
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="category_slug" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Category</label>
                    <select id="category_slug" name="category_slug" required style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                        <option value="" disabled>Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ old('category_slug', $item->category->slug) === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_slug')
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="description" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Description</label>
                    <textarea id="description" name="description" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px; resize: vertical;" placeholder="Enter item description">{{ old('description', $item->description) }}</textarea>
                    @error('description')
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="image" style="display: block; font-size: 14px; color: #ccc; margin-bottom: 4px;">Image (Current: {{ $item->image ? 'Uploaded' : 'None' }})</label>
                    @if($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->name }}" style="max-width: 100px; border-radius: 6px; margin-bottom: 10px;">
                    @endif
                    <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 10px; background: #2c2c2c; border: none; color: white; border-radius: 6px; font-size: 14px;">
                    @error('image')
                        <div style="color: #e57373; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #722be0; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500;">
                        <i data-feather="save"></i> Update Item
                    </button>
                    <a href="{{ route('items.viewIndex') }}" style="background: #2c2c2c; color: #ccc; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="x"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
