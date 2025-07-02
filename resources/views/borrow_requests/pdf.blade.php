<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->borrow_date_expected }}</td>
                <td>{{ $request->return_date_expected }}</td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>
                    @foreach ($request->borrowDetails as $detail)
                    {{ $detail->itemUnit->item->name }} ({{ $detail->quantity }})<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
