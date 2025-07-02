<!DOCTYPE html>
<html>
<head>
    <title>Items Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #722be0;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        img {
            max-width: 50px;
            max-height: 50px;
        }
    </style>
</head>
<body>
    <h2>Items Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Category</th>
                <th>Description</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->type === 'consumable' ? 'Consumable' : 'Non-Consumable' }}</td>
                    <td>{{ $item->category ? $item->category->name : '-' }}</td>
                    <td>{{ $item->description ?? '-' }}</td>
                    <td>
                        @if($item->image)
                            <img src="{{ public_path($item->image) }}" alt="{{ $item->name }}">
                        @else
                            No Image
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
