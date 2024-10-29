<x-app-layout>
    <style>
        /* Your existing styles */
        .print-adjust {
            margin: 0 auto; /* Center the container horizontally */
            padding: 20px; /* Optional: Add padding for better visual appearance */
            width: 80%; /* You can adjust this width according to your preference */
        }

        .left-adjust {
            margin-left: 0px; /* Adjust this value to shift content to the left */
            width: 90%; /* Optionally set a width for your content */
        }

        .logo {
            /*text-align: center; /* Center the logo on the page */
            margin-bottom: 20; /* Adds space below the logo */
        }

        .logo img {
            width: 1000px; /* Ensures the logo fits within the container */
            height: auto; /* Adjusts the height automatically */
        }
    </style>

    <div class="container">
        <!-- Move the logo to the top -->
        <div class="logo">
            <img src="{{ asset('images/ceno-logo.png') }}" alt="Company Logo" />
        </div>

        <h6>Assets Report</h6>

        <div class="statements">
            <p>All assets listed in this report are subject to periodic review and audit. For any discrepancies or updates, please contact the asset management department.</p>
        </div>

        <br><br>

        <div class="report-actions print-hide">
            <a href="{{ route('reports.index') }}" class="btn btn-primary mr-2">Back</a>
            <a href="/reports/export/pdf" class="btn btn-primary">PDF</a>
            <a href="/reports/export/excel" class="btn btn-success">Excel</a>
            <a href="/reports/export/word" class="btn btn-info">Word</a>
            <button id="printButton" class="btn btn-secondary">Print</button>
        </div>

        <!-- Assets Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Asset No</th>
                    <th>Category</th>
                    <th>Current User</th>
                    <th>Date</th>
                    <th>Previous User</th>
                    <th>Vendor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->make }}</td>
                        <td>{{ $asset->model }}</td>
                        <td>{{ $asset->serial_number }}</td>
                        <td>{{ $asset->asset_number }}</td>
                        <td>{{ $asset->category }}</td>
                        <td>{{ $asset->users ? $asset->users->name : 'N/A' }}</td>
                        <td>{{ $asset->date }}</td>
                        <td>{{ $asset->users ? optional($asset->previousUser)->name : 'N/A' }}</td>
                        <td>{{ $asset->vendor }}</td>
                        <td>{{ $asset->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            // Hide elements with the class 'print-hide'
            document.querySelectorAll('.print-hide, .sidebar, .navbar').forEach(function (element) {
                element.style.display = 'none';
            });

            // Adjust the report page to move to the left
            document.querySelector('.container').classList.add('print-adjust');

            // Print the page
            window.print();

            // Show the hidden elements again after printing
            document.querySelectorAll('.print-hide, .sidebar, .navbar').forEach(function (element) {
                element.style.display = '';
            });

            // Remove the adjustment class after printing
            document.querySelector('.container').classList.remove('print-adjust');
        });
    </script>
</x-app-layout>
