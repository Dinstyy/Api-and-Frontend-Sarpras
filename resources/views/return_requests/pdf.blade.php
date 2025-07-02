<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Data Pengembalian</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Nama Barang</th>
                <th>SKU Unit</th>
                <th>Kondisi</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Tanggal Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnRequests as $request)
                @foreach ($request->returnDetails as $detail)
                    <tr>
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->returnRequest->borrowRequest->user->username ?? '-' }}</td>
                        <td>{{ $detail->itemUnit->item->name ?? '-' }}</td>
                        <td>{{ $detail->itemUnit->sku ?? '-' }}</td>
                        <td>{{ $detail->condition }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->notes ?? '-' }}</td>
                        <td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
