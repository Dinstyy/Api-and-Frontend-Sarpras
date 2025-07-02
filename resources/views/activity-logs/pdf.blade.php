<!DOCTYPE html>
<html>

<head>
    <title>Item Units PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        img {
            width: 60px;
            height: 60px;
        }
    </style>
</head>

<body>
    <h2>Activity Logs</h2>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->user?->username ?? '-' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
