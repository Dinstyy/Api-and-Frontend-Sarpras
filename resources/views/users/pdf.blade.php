<!DOCTYPE html>
<html>
<head>
    <title>Users PDF</title>
</head>
<body>
    <h1>Daftar Pengguna</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Kelas</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->kelas }}</td>
                <td>{{ $user->role }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
