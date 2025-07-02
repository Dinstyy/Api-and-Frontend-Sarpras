@extends('layouts.app')

@section('title', 'Item Units')
@section('icon', 'file-text')

@if (session('error'))
    <div style="color: red; margin-bottom: 16px;">{{ session('error') }}</div>
@endif

@section('content')
    <div class="page-intro">
        <h2>Item Units</h2>
        <p>Manage your inventory item units here.</p>
    </div>

    <div class="content">
        <div style="width: 100%;">
            <div class="search-form">
                <input type="text" id="search-input" class="search-input" placeholder="Cari berdasarkan unit code, merk, kondisi, atau lokasi...">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 16px;">
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('item-units.create') }}" style="background: #722be0; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="plus"></i> Create Item Unit
                    </a>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('item-units.exportExcel') }}" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="download"></i> Export Excel
                    </a>
                    <a href="{{ route('item-units.exportPdf') }}" style="background: #e11d48; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500;">
                        <i data-feather="download"></i> Export PDF
                    </a>
                    <form action="{{ route('item-units.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label style="background: #2c2c2c; color: #ccc; padding: 8px 16px; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            <i data-feather="upload"></i> Import Item Units
                            <input type="file" name="file" style="display: none;" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; background: #111; border-radius: 8px; overflow: hidden;" id="itemUnitsTable">
                <thead style="background-color: #722be0;">
                    <tr>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Unit Code</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Merk</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Condition</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Item</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Warehouse</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Status</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Quantity</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">QR Code</th>
                        <th style="padding: 12px; text-align: left; font-size: 14px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="itemUnitTableBody">
                    @forelse ($itemUnits as $itemUnit)
                        <tr>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->unit_code }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->merk }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->condition }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->item->name }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->warehouse->name }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ ucfirst($itemUnit->status) }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd;">{{ $itemUnit->quantity }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #ddd; text-align: center;">
                                @php
                                    $qrText =
                                        "Unit Code: {$itemUnit->unit_code}\n" .
                                        "Item: {$itemUnit->item->name}\n" .
                                        "Category: " . ($itemUnit->item->category->name ?? 'N/A') . "\n" .
                                        "Merk: {$itemUnit->merk}\n" .
                                        "Condition: {$itemUnit->condition}\n" .
                                        "Warehouse: " . ($itemUnit->warehouse->name ?? 'N/A') . "\n" .
                                        "Status: " . ucfirst($itemUnit->status) . "\n" .
                                        "Quantity: {$itemUnit->quantity}\n" .
                                        "Current Location: " . ($itemUnit->current_location ?? 'N/A');
                                @endphp

                                <div class="bg-black p-1 rounded border inline-block p-1">
                                    @if ($itemUnit->qr_image && file_exists(public_path('storage/' . $itemUnit->qr_image)))
                                        <img src="{{ asset('storage/' . $itemUnit->qr_image) }}" alt="QR {{ $itemUnit->unit_code }}" style="width: 50px; height: 50px;">
                                    @else
                                        <span style="color: red; font-size: 12px;">QR tidak tersedia</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px; font-size: 14px; display: flex; gap: 8px;">
                                <a href="{{ route('item-units.showView', $itemUnit->unit_code) }}" style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                                    <i data-feather="eye"></i> View
                                </a>
                                <a href="{{ route('item-units.edit', $itemUnit->unit_code) }}" style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                                    <i data-feather="edit"></i> Edit
                                </a>
                                <form action="{{ route('item-units.destroy', $itemUnit->unit_code) }}" method="POST" style="display: inline;" onsubmit="return handleDeleteSubmit(this)">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="delete_token" value="{{ uniqid() }}">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item unit?')" style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                                        <i data-feather="trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 12px; text-align: center;">No item units found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 16px;" id="pagination-links">
                {{ $itemUnits->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search-input");
            const itemUnitTableBody = document.getElementById("itemUnitTableBody");
            const paginationLinks = document.getElementById("pagination-links");

            searchInput.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = itemUnitTableBody.querySelectorAll("tr");

                if (searchTerm === "") {
                    rows.forEach(row => row.style.display = "");
                    paginationLinks.style.display = "block";
                    return;
                }

                paginationLinks.style.display = "none";

                let hasResults = false;
                rows.forEach(row => {
                    const unitCode = row.querySelector("td:nth-child(1)")?.textContent.toLowerCase() || '';
                    const merk = row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() || '';
                    const condition = row.querySelector("td:nth-child(3)")?.textContent.toLowerCase() || '';
                    const item = row.querySelector("td:nth-child(4)")?.textContent.toLowerCase() || '';
                    const warehouse = row.querySelector("td:nth-child(5)")?.textContent.toLowerCase() || '';
                    const status = row.querySelector("td:nth-child(6)")?.textContent.toLowerCase() || '';
                    const quantity = row.querySelector("td:nth-child(7)")?.textContent.toLowerCase() || '';

                    const matches = (
                        unitCode.includes(searchTerm) ||
                        merk.includes(searchTerm) ||
                        condition.includes(searchTerm) ||
                        item.includes(searchTerm) ||
                        warehouse.includes(searchTerm) ||
                        status.includes(searchTerm) ||
                        quantity.includes(searchTerm)
                    );

                    row.style.display = matches ? "" : "none";
                    if (matches) hasResults = true;
                });

                if (!hasResults) {
                    itemUnitTableBody.innerHTML = `
                        <tr>
                            <td colspan="9" style="padding: 12px; text-align: center;">Tidak ada item unit ditemukan.</td>
                        </tr>
                    `;
                }
            });
        });

        function handleDeleteSubmit(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Prevent double submission
            return true;
        }
    </script>
@endsection
