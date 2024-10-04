<!DOCTYPE html>
<html>
<head>
    <title>Insurance Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header img {
            width: 700px; /* Adjust the width as needed */
            height: 100px; /* Adjust the height as needed */
        }
        .report-header h4 {
            margin: 0;
            padding: 10px 0;
        }

    </style>
</head>
<body>
    <div class="report-header">
        <img src="images/ceno-logo.png" alt="Company Logo">
        <h4>Insurance Report</h4>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Assigned To</th>
                <th>Policy Number</th>
                <th>Status</th>
                <th>Claimed Date</th>
                <th>Rejected Date</th>
                <th>Approval Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($insurances as $insurance)
                <tr>
                    <td>{{ $insurance->id }}</td>
                    <td>{{ $insurance->asset ? $insurance->asset->make : 'N/A' }}</td>
                    <td>{{ $insurance->user ? $insurance->user->name : 'N/A' }}</td>
                    <td>{{ $insurance->claim_number }}</td>
                    <td>{{ $insurance->status }}</td>
                    <td>{{ $insurance->claim_date }}</td>
                    <td>{{ $insurance->rejection_date }}</td>
                    <td>{{ $insurance->approval_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
