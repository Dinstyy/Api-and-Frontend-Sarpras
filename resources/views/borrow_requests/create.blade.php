@extends('layouts.app')

@section('title', 'Buat Permintaan Peminjaman')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="page-intro">
        <h2 class="text-2xl font-bold mb-4">Buat Permintaan Peminjaman</h2>
    </div>

    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('borrow-requests.store') }}" method="POST" id="borrow-form">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Peminjam</label>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Nama Peminjam</label>
                <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Masukkan nama peminjam jika eksternal">
                @error('borrower_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Tanggal Pinjam</label>
                <input type="date" name="borrow_date_expected" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" value="{{ old('borrow_date_expected') }}">
                @error('borrow_date_expected')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Tanggal Kembali</label>
                <input type="date" name="return_date_expected" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" value="{{ old('return_date_expected') }}">
                @error('return_date_expected')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Alasan</label>
                <textarea name="reason" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">{{ old('reason') }}</textarea>
                @error('reason')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Catatan (Opsional)</label>
                <textarea name="notes" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Item yang Dipinjam</label>
                <div id="item-rows">
                    <div class="item-row">
                        <select name="items[0][item_unit_id]" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">
                            <option value="">Pilih Item</option>
                            @foreach ($itemUnits as $itemUnit)
                                <option value="{{ $itemUnit->id }}">{{ $itemUnit->item->name }} ({{ $itemUnit->item->type === 'consumable' ? 'Stok: ' . $itemUnit->quantity : 'Status: ' . $itemUnit->status }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][quantity]" min="1" value="1" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Kuantitas">
                        <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded">-</button>
                    </div>
                </div>
                <button type="button" id="add-item" class="bg-green-500 text-white px-4 py-2 rounded mt-2">+ Tambah Item</button>
                @error('items')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#user_id').select2({
            placeholder: "Pilih Peminjam (Ketik untuk mencari)",
            allowClear: true,
            width: '100%'
        });

        // Handle user_id change
        $('#user_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const userName = selectedOption.data('name');
            const borrowerNameInput = $('#borrower_name');

            if ($(this).val()) {
                borrowerNameInput.val(userName || '');
                borrowerNameInput.prop('disabled', true);
            } else {
                borrowerNameInput.val('');
                borrowerNameInput.prop('disabled', false);
            }
        });

        // Trigger change on page load
        $('#user_id').trigger('change');

        // Form validation
        $('#borrow-form').on('submit', function(e) {
            const userId = $('#user_id').val();
            const borrowerName = $('#borrower_name').val().trim();

            if (!userId && !borrowerName) {
                e.preventDefault();
                alert('Nama Peminjam wajib diisi.');
                $('#borrower_name').focus();
            }
        });

        // Add item row
        let itemIndex = 1;
        $('#add-item').click(function() {
            const itemRow = `
                <div class="item-row">
                    <select name="items[${itemIndex}][item_unit_id]" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white">
                        <option value="">Pilih Item</option>
                        @foreach ($itemUnits as $itemUnit)
                            <option value="{{ $itemUnit->id }}">{{ $itemUnit->item->name }} ({{ $itemUnit->item->type === 'consumable' ? 'Stok: ' . $itemUnit->quantity : 'Status: ' . $itemUnit->status }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="w-full border rounded px-3 py-2 bg-[#2c2c2c] text-white" placeholder="Kuantitas">
                    <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded">-</button>
                </div>
            `;
            $('#item-rows').append(itemRow);
            itemIndex++;
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
        });
    });
</script>
@endsection
