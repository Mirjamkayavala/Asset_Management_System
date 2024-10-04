<x-app-layout>
    <style>
        @media print {
            /* Hide the sidebar, navbar, filter form, and export buttons when printing */
            .sidebar, .navbar, form, .mt-3 {
                display: none;
            }

            /* Make the report content take full width and align to the left */
            .container {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            /* Ensure the report table covers the whole page and aligns to the left */
            .card {
                margin-left: 0;
                width: 100%;
            }

            /* Make sure the footer is visible when printing */
            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 12px;
            }
        }
    </style>

    <div class="container">
        <!-- Report Header -->
        <img src="{{ asset('images/ceno-logo.png') }}" alt="Company Logo" width="1000" height="200">
        <br><br>

        <!-- Filter Form (Hidden when printing) -->
        <form method="GET" class="mb-3" action="{{ route('insurances.filter') }}">
            <!-- Filter fields here -->
        </form>
        <br><br>

        <!-- Export and Print Buttons (Hidden when printing) -->
        <div class="mt-3">
            <a href="{{ route('insurances.export.pdf', request()->query()) }}" class="btn btn-danger">Export to PDF</a>
            <a href="{{ route('insurances.export.excel', request()->query()) }}" class="btn btn-success">Export to Excel</a>
            <a href="{{ route('insurances.export.word', request()->query()) }}" class="btn btn-warning">Export to Word</a>
            <button onclick="window.print()" class="btn btn-info">Print</button>
        </div>
    </div>

    <!-- Insurance Report Section -->
    <div class="card mt-5">
        <h5>Insurance Report</h5>
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

</x-app-layout>
