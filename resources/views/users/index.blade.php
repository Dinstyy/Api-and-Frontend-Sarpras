@extends('layouts.app')

@section('title', 'Users')
@section('icon', 'users')

@section('content')
    <h2 style="margin-bottom: 16px; font-size: 18px;">Daftar Pengguna</h2>
    <div class="search-form">
        <input type="text" id="search-input" class="search-input" placeholder="Cari berdasarkan nama, kelas, role, email, atau username...">
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 16px;">
        <div>
            <a href="{{ route('users.create') }}" style="background: #722be0; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
                + Tambah Pengguna
            </a>
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" style="display: inline-block;">
                @csrf
                <input type="file" name="file" accept=".xlsx,.csv" required style="padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px; margin-right: 8px;">
                <button type="submit" style="background: #28a745; color: white; padding: 10px 18px; border-radius: 6px; border: none; font-size: 14px; font-weight: 500; cursor: pointer;">
                    Import Users
                </button>
            </form>
        </div>
        <div>
            <a href="{{ route('users.exportExcel') }}" style="background: #28a745; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 14px; margin-right: 8px;">
                Export Excel
            </a>
            <a href="{{ route('users.exportPdf') }}" target="_blank" style="background: #dc3545; color: white; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                Export PDF
            </a>
        </div>
    </div>
    <table style="width: 100%; border-collapse: collapse; color: white;" id="usersTable">
        <thead style="background-color: #722be0;">
            <tr>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">No</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Nama</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Username</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Email</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Kelas</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Role</th>
                <th style="font-size: 15px; text-align: left; padding: 12px; border-bottom: 1px solid #333;">Aksi</th>
            </tr>
        </thead>
        <tbody id="user-table-body">
            @forelse ($users as $index => $user)
                <tr style="border-bottom: 1px solid #2c2c2c;">
                    <td style="padding: 12px;">{{ $users->firstItem() + $index }}</td>
                    <td style="padding: 12px;">{{ $user->name }}</td>
                    <td style="padding: 12px;">{{ $user->username ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $user->email }}</td>
                    <td style="padding: 12px;">{{ $user->kelas ?? '-' }}</td>
                    <td style="padding: 12px; text-transform: capitalize;">{{ $user->role }}</td>
<td style="padding: 12px; display: flex; gap: 8px;">
    <a href="{{ route('users.show', $user->id) }}"
       style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF7E6; color: #D18616; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
        <i data-feather="eye" style="font-size: 16px;"></i>
        Lihat
    </a>
    @if ($user->id !== Auth::id())
        <a href="{{ route('users.edit', $user->id) }}"
           style="display: inline-flex; align-items: center; gap: 6px; background-color: #F0F4FF; color: #4C6EF5; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
            <i data-feather="edit" style="font-size: 16px;"></i>
            Edit
        </a>
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #FFF0F0; color: #E03131; font-weight: 600; font-size: 14px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer;">
                <i data-feather="trash" style="font-size: 16px;"></i>
                Hapus
            </button>
        </form>
    @endif
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 12px; text-align: center;">Tidak ada pengguna ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 16px;" id="pagination-links">
        {{ $users->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search-input");
            const userTableBody = document.getElementById("user-table-body");
            const paginationLinks = document.getElementById("pagination-links");

            searchInput.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = userTableBody.querySelectorAll("tr");

                if (searchTerm === "") {
                    rows.forEach(row => row.style.display = "");
                    paginationLinks.style.display = "block";
                    return;
                }

                paginationLinks.style.display = "none";

                let hasResults = false;
                rows.forEach(row => {
                    const name = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    const username = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                    const email = row.querySelector("td:nth-child(4)").textContent.toLowerCase();
                    const kelas = row.querySelector("td:nth-child(5)").textContent.toLowerCase();
                    const role = row.querySelector("td:nth-child(6)").textContent.toLowerCase();

                    const matches = (
                        name.includes(searchTerm) ||
                        username.includes(searchTerm) ||
                        email.includes(searchTerm) ||
                        kelas.includes(searchTerm) ||
                        role.includes(searchTerm)
                    );

                    row.style.display = matches ? "" : "none";
                    if (matches) hasResults = true;
                });

                if (!hasResults) {
                    userTableBody.innerHTML = `
                        <tr>
                            <td colspan="7" style="padding: 12px; text-align: center;">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    `;
                }
            });
        });
    </script>
@endsection
