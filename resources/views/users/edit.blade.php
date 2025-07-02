@extends('layouts.app')

@section('content')
<div class="content">
    <h2 style="margin-bottom: 16px; font-size: 18px;">Edit Pengguna</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST" style="background: #1a1a1a; padding: 20px; border-radius: 8px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;"
                   {{ $user->role === 'admin' ? '' : 'required' }}>
            @error('name')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        @if(in_array($user->role, ['siswa', 'guru', 'kepsek', 'caraka']))
        <div style="margin-bottom: 15px;">
            <label for="username" style="display: block; margin-bottom: 5px;">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('username')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('email')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        @if($user->role === 'siswa')
        <div style="margin-bottom: 15px;">
            <label for="kelas" style="display: block; margin-bottom: 5px;">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $user->kelas) }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;" required>
            @error('kelas')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div style="margin-bottom: 15px;">
            <label for="role" style="display: block; margin-bottom: 5px;">Role</label>
            <select name="role" id="role" style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
                @foreach(['admin', 'siswa', 'guru', 'kepsek', 'caraka'] as $role)
                    <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="password" style="display: block; margin-bottom: 5px;">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password baru"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('password')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #722be0; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">
                Simpan
            </button>
            <a href="{{ route('users.viewIndex') }}" style="background: #333; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
