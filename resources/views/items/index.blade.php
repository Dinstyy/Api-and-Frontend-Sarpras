@extends('layouts.app')

@section('title', 'Items')
@section('icon', 'file')

@if (session('error'))
    <div style="color: red; margin-bottom: 16px;">{{ session('error') }}</div>
@endif

@section('content')
    <div class="page-intro">
        <h2>Items</h2>
        <p>Manage your inventory items here.</p>
    </div>

    <div class="content">
        <div style="width: 100%;">
            <div class="search-form">
                <input type="text" id="search-input" class="search-input" placeholder="Cari berdasarkan nama, tipe, kategori, atau deskripsi...">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 16px;">
                <div>
                    <a href="{{ route('items.create') }}" style="background: #722be0; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="plus"></i> Create Item
                    </a>
                </div>
                <div style="display: flex; gap: 10px;">
                    <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label style="background: #2c2c2c; color: #ccc; padding: 8px 16px; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            <i data-feather="upload"></i> Import Items
                            <input type="file" name="file" style="display: none;" onchange="this.form.submit()">
                        </label>
                    </form>
                    <a href="{{ route('items.exportExcel') }}" style="background: #28a745; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                        <i data-feather="download"></i> Export Excel
                    </a>
                    <a href="{{ route('items.exportPdf') }}" style="background: #dc3545; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                        <i data-feather="file-text"></i> Export PDF
                    </a>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; background: #111; border-radius: 8px; overflow: hidden;" id="itemsTable">
                <thead style="background-color: #722be0;">
                    <tr>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">ID</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Name</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Type</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Category</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Description</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Image</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="itemTableBody">
                    @forelse ($items as $item)
                        <tr>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $item->id }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $item->name }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $item->type === 'consumable' ? 'Consumable' : 'Non-Consumable' }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $item->category ? $item->category->name : '-' }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $item->description ?? '-' }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}" style="max-width: 50px; max-height: 50px; border-radius: 4px; object-fit: cover;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td style="padding: 12px; font-size: 14px; display: flex; gap: 8px;">
                                <a href="{{ route('items.showView', $item->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                                    <i data-feather="eye"></i> View
                                </a>
                                <a href="{{ route('items.edit', $item->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                                    <i data-feather="edit"></i> Edit
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')" style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                                        <i data-feather="trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 12px; text-align: center;">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 16px;" id="pagination-links">
                {{ $items->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search-input");
            const itemTableBody = document.getElementById("itemTableBody");
            const paginationLinks = document.getElementById("pagination-links");

            searchInput.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = itemTableBody.querySelectorAll("tr");

                if (searchTerm === "") {
                    rows.forEach(row => row.style.display = "");
                    paginationLinks.style.display = "block";
                    return;
                }

                paginationLinks.style.display = "none";

                let hasResults = false;
                rows.forEach(row => {
                    const name = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    const type = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                    const category = row.querySelector("td:nth-child(4)").textContent.toLowerCase();
                    const description = row.querySelector("td:nth-child(5)").textContent.toLowerCase();

                    const matches = (
                        name.includes(searchTerm) ||
                        type.includes(searchTerm) ||
                        category.includes(searchTerm) ||
                        description.includes(searchTerm)
                    );

row.style.display = matches ? "" : "none";
                    if (matches) hasResults = true;
                });

                if (!hasResults) {
                    itemTableBody.innerHTML = `
                        <tr>
                            <td colspan="7" style="padding: 12px; text-align: center;">Tidak ada item ditemukan.</td>
                        </tr>
                    `;
                }
            });
        });
    </script>
@endsection
