@extends('layouts.app')

@section('content')
<div class="content">
    <h2 style="margin-bottom: 16px; font-size: 18px;">Tambah Pengguna</h2>
    <form action="{{ route('users.store') }}" method="POST" style="background: #1a1a1a; padding: 20px; border-radius: 8px;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="role" style="display: block; margin-bottom: 5px;">Role</label>
            <select name="role" id="role" style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;" required onchange="toggleFields()">
                @foreach(['admin', 'siswa', 'guru', 'kepsek', 'caraka'] as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;" required>
            @error('name')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px; display: none;" id="emailField">
            <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('email')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px; display: none;" id="passwordField">
            <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('password')
                <span style="color: #e57373; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 15px; display: none;" id="kelasField">
            <label for="kelas" style="display: block; margin-bottom: 5px;">Kelas</label>
            <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}"
                   style="width: 100%; padding: 8px; background: #2c2c2c; border: none; color: white; border-radius: 4px;">
            @error('kelas')
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

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    const emailField = document.getElementById('emailField');
    const passwordField = document.getElementById('passwordField');
    const kelasField = document.getElementById('kelasField');

    emailField.style.display = 'none';
    passwordField.style.display = 'none';
    kelasField.style.display = 'none';
    document.getElementById('email').removeAttribute('required');
    document.getElementById('password').removeAttribute('required');
    document.getElementById('kelas').removeAttribute('required');

    if (['admin', 'kepsek'].includes(role)) {
        emailField.style.display = 'block';
        document.getElementById('email').setAttribute('required', 'required');
        if (['kepsek'].includes(role)) {
            passwordField.style.display = 'block';
            document.getElementById('password').setAttribute('required', 'required');
        }
    } else if (role === 'siswa') {
        kelasField.style.display = 'block';
        document.getElementById('kelas').setAttribute('required', 'required');
    }
}
toggleFields();
</script>
@endsection
