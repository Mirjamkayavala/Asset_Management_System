<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Report</title>
    <style>
       
        body {
            font-family: nexa;
            font-size: 16px;
        }

        @media print {
            /* Hide the filter form and non-report elements when printing */
            .filter-form, .mt-3, .sidebar, .navbar {
                display: none !important;
            }

            /* Ensure the report content takes full width and is centered */
            .container {
                margin: 0 auto;
                padding: 0;
                width: 100%;
            }

            /* Ensure the report card covers full width */
            .card {
                width: 100%;
                margin: auto;
                padding: 10px;
            }

            /* Ensure the footer is visible when printing */
            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 12px;
            }
        }

        /* Set global styles */
        body {
            font-family: 'Nexa'; /* Set the font to Nexa */
            font-size: 16px; /* Set font size to 12px */
        }

        /* Ensure the container is centered and covers the full page width */
        .container {
            margin: 0 auto !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            max-width: 100%;
        }

        /* Center the report card */
        .card {
            margin: 0 auto;
            width: 100%;
            padding: 15px;
        }

        /* Add border to the table */
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

        /* Centering the h4 element specifically */
        h4 {
            text-align: center; /* Center the heading */
        }

        /* Filter form styling */
        .filter-form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-form div {
            display: flex;
            flex-direction: column;
        }

        .filter-form button {
            height: 100%;
        }

        /* Logo Styling */
        .logo img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Report Header with Full Width Logo -->
        <div class="logo">
            <img src="{{ asset('images/ceno.png') }}" alt="Company Logo">
        </div>
        <br><br>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <div>
                <label for="claim_number">Policy Number</label>
                <input type="text" name="claim_number" id="claim_number" value="{{ request()->claim_number }}" placeholder="Enter Policy Number">
            </div>
            <div>
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="approved" {{ request()->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="claimed" {{ request()->status == 'claimed' ? 'selected' : '' }}>Claimed</option>
                    <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request()->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
        <br><br>

        <!-- Export and Print Buttons -->
        <div class="mt-3">
            <form action="{{ route('insurances.export.pdf', request()->query()) }}" method="GET" style="display:inline;">
                <button type="submit" class="btn btn-danger">Export to PDF</button>
            </form>

            <form action="{{ route('insurances.export.excel', request()->query()) }}" method="GET" style="display:inline;">
                <button type="submit" class="btn btn-success">Export to Excel</button>
            </form>

            <form action="{{ route('insurances.export.word', request()->query()) }}" method="GET" style="display:inline;">
                <button type="submit" class="btn btn-warning">Export to Word</button>
            </form>

            <button onclick="window.print()" class="btn btn-info">Print</button>
            <!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary mr-2">Back</a> -->
            <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100" style="background-color: #008CBA; border: 2px solid #000;">Back</a>

        </div>
    </div>

    <!-- Insurance Report Section -->
    <div class="card mt-5">
        <h4 style="text-align: center;">Insurance Report</h4>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Make</th>
                        <th>Policy Number</th>
                        <th>Insurance Type</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Claimed Date</th>
                        <th>Approved Date</th>
                        <th>Rejected Date</th>
                    </tr>
                </thead>
                <tbody id="insuranceTableBody">
                    @foreach ($insurances as $insurance)
                        <tr>
                            <td>{{ $insurance->id }}</td>
                            <td>{{ $insurance->asset ? $insurance->asset->make : 'N/A' }}</td>
                            <td>{{ $insurance->claim_number }}</td>
                            <td>{{ $insurance->insurance_type }}</td>
                            <td>{{ ucfirst($insurance->status) }}</td>
                            <td>{{ $insurance->amount }}</td>
                            <td>{{ $insurance->claim_date }}</td>
                            <td>{{ $insurance->approval_date }}</td>
                            <td>{{ $insurance->rejection_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>
