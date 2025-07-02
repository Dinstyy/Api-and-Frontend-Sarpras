@extends('layouts.app')

@section('title', 'Dashboard')
@section('icon', 'home')

@section('content')
    <div class="page-intro">
        <h2>Welcome, Adminᶻ 𝗓 𐰁</h2>
        <div id="live-datetime" style="color: #ccc; font-size: 14px; margin-top: 8px; display: flex; align-items: center; gap: 8px;">
            <i data-feather="clock"></i> <!-- Ikon jam di sisi kiri -->
            <span id="datetime-text"></span> <!-- Tempat untuk teks waktu -->
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-header">
                <span>Total User</span>
                <span class="card-icon blue"><i data-feather="users"></i></span>
            </div>
            <div class="card-body">
                <div class="card-value">0</div>
                <div class="card-footer">0%   vs periode sebelumnya</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span>Total Barang</span>
                <span class="card-icon green"><i data-feather="box"></i></span>
            </div>
            <div class="card-body">
                <div class="card-value">0</div>
                <div class="card-footer"><span style="color: #00cc66;">↗ 0%</span>   vs periode sebelumnya</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span>Peminjaman</span>
                <span class="card-icon orange"><i data-feather="clock"></i></span>
            </div>
            <div class="card-body">
                <div class="card-value">0</div>
                <div class="card-footer">0%   vs periode sebelumnya</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span>Pengembalian</span>
                <span class="card-icon pink"><i data-feather="archive"></i></span>
            </div>
            <div class="card-body">
                <div class="card-value">0</div>
                <div class="card-footer">0%   vs periode sebelumnya</div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Jakarta' // WIB
            };
            const formattedDateTime = now.toLocaleString('id-ID', options).replace('pukul', 'Jam');
            document.getElementById('datetime-text').textContent = formattedDateTime; // Update hanya teks waktu
        }

        // Update setiap detik
        setInterval(updateDateTime, 1000);
        // Panggil sekali saat halaman dimuat
        updateDateTime();
    </script>
@endsection
