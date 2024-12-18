<!DOCTYPE html>
<html>
<head>
    <title>Assets Report</title>
    <link rel="stylesheet" href="http://127.0.0.1:8000/css/bootstrap-5.3.3.min.css" integrity="sha384-xyz" crossorigin="anonymous">
    <style>
        .filter-form { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; align-items: end; margin-bottom: 20px; }
        .filter-form div { display: flex; flex-direction: column; }
        .filter-form button { height: 100%; }
        .report-actions { margin-bottom: 20px; }
        .report-actions a { margin-right: 10px; }
        .logo { text-align: left; margin-bottom: 20px; }
        .statements { text-align: left; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: blue; color: white; }
    </style>
</head>
<body>
    <div class="logo">
        <!-- Use absolute path to ensure PDF can load the image -->
        <img src="{{ public_path('images/ceno.png') }}" alt="Company Logo" width="700" height="100"/>
    </div>
    <div class="statements">
        <p>All assets listed in this report are subject to periodic review and audit. For any discrepancies or updates, please contact the asset management department.</p>
    </div>

    <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Serial Number</th>
            <th>Asset No</th>
            <th>Category</th>
            <th>Assign To</th>
            <th>Date</th>
            <th>Vendor</th>
            <th>Facility Space</th>
            <th>Location</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset['make'] }}</td>
                <td>{{ $asset['model'] }}</td>
                <td>{{ $asset['serial_number'] }}</td>
                <td>{{ $asset['asset_number'] }}</td>
                <td>{{ $asset['category_name'] }}</td>
                <td>{{ $asset['user_name'] }}</td>
                <td>{{ \Carbon\Carbon::parse($asset['date'])->format('Y-m-d H:i:s') }}</td>
                
                <td>{{ $asset['vendor'] }}</td>
                <td>{{ $asset['facility'] }}</td>
                <td>{{ $asset['location_name'] }}</td>
                <td>{{ $asset['status'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
