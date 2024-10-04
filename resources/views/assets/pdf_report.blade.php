<!DOCTYPE html>
<html>
<head>
    <title>Asset Report</title>
</head>
<body>
    <h1>Asset Report ({{ $startDate }} to {{ $endDate }})</h1>
    <h2>Location: {{ $location }}</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Asset Name</th>
                <th>Status</th>
                <th>Location</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->status }}</td>
                <td>{{ $asset->location }}</td>
                <td>{{ $asset->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
