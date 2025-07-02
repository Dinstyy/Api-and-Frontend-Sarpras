@extends('layouts.app')

@section('content')
<div class="content">
    <h2 style="margin-bottom: 16px; font-size: 18px;">Detail Pengguna</h2>
    <div style="background: #1a1a1a; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Username:</strong> {{ $user->username ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
        <p><strong>Kelas:</strong> {{ $user->kelas ?? '-' }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Created At:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
        <a href="{{ route('users.viewIndex') }}" style="background: #333; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
