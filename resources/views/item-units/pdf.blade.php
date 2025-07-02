<!DOCTYPE html>
<html>
<head>
    <title>Item Units Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #722be0; color: white; }
        h1 { text-align: center; }
        img.qr-code { width: 50px; height: 50px; object-fit: contain; }
    </style>
</head>
<body>
    <h1>Item Units Report</h1>
    <table>
        <thead>
            <tr>
                <th>Unit Code</th>
                <th>Merk</th>
                <th>Condition</th>
                <th>Item</th>
                <th>Warehouse</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Diperoleh Dari</th>
                <th>Diperoleh Tanggal</th>
                <th>Current Location</th>
                <th>Notes</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemUnits as $itemUnit)
                <tr>
                    <td>{{ $itemUnit->unit_code }}</td>
                    <td>{{ $itemUnit->merk }}</td>
                    <td>{{ $itemUnit->condition }}</td>
                    <td>{{ $itemUnit->item->name }}</td>
                    <td>{{ $itemUnit->warehouse->name }}</td>
                    <td>{{ ucfirst($itemUnit->status) }}</td>
                    <td>{{ $itemUnit->quantity }}</td>
                    <td>{{ $itemUnit->diperoleh_dari }}</td>
                    <td>{{ $itemUnit->diperoleh_tanggal }}</td>
                    <td>{{ $itemUnit->current_location ?? 'N/A' }}</td>
                    <td>{{ $itemUnit->notes ?? 'N/A' }}</td>
                    <td>
                        @if ($itemUnit->qr_image && file_exists(public_path('storage/' . str_replace('/storage/', '', $itemUnit->qr_image))))
                            <img src="{{ public_path('storage/' . str_replace('/storage/', '', $itemUnit->qr_image)) }}" class="qr-code" alt="QR Code" />
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
