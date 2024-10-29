<x-app-layout>
    <div class="container">

        <!-- Display errors if any -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Insurance Claim Details -->
        <h6>Insurance Claim Details</h6>
        <div class="card">
            <div class="card-header">
                Claim #{{ $insurance->claim_number ?? 'N/A' }}
            </div>

            <div class="card-body">
                <table class="table table-bordered">

                    <tr>
                        <th>Make</th>
                        <td>{{ $insurance->asset ? $insurance->asset->make : 'No asset assigned' }}</td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td>{{ $insurance->asset ? $insurance->asset->serial_number : 'No asset assigned' }}</td>
                    </tr>
                    <!-- Policy Number -->
                    <tr>
                        <th>Policy Number</th>
                        <td>{{ $insurance->claim_number }}</td>
                    </tr>

                    <!-- Insurance Type -->
                    <tr>
                        <th>Insurance Type</th>
                        <td>{{ $insurance->insurance_type }}</td>
                    </tr>

                    <!-- Asset -->


                    <!-- Assigned To -->
                    <tr>
                        <th>Assigned To</th>
                        <td>{{ $insurance->user ? $insurance->user->name : 'No user assigned' }}</td>
                    </tr>

                    <!-- Status -->
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($insurance->status ?? 'Unknown') }}</td>
                    </tr>

                    <!-- Comments -->
                    <tr>
                        <th>Comments</th>
                        <td>{{ $insurance->description }}</td>
                    </tr>

                    <!-- Claim Date -->
                    <tr>
                        <th>Claim Date</th>
                        <td>{{ $insurance->claim_date }}</td>
                    </tr>

                    <!-- Approval Date -->
                    <tr>
                        <th>Approval Date</th>
                        <td>{{ $insurance->approval_date}}</td>
                    </tr>

                    <!-- Rejection Date -->
                    <tr>
                        <th>Rejection Date</th>
                        <td>{{ $insurance->rejection_date}}</td>
                    </tr>

                    <!-- Insurance Documents -->
                    <tr>
                        <th>Insurance Documents</th>
                        <td>
                            @if ($insurance->insurance_document)
                                <a href="{{ asset('storage/' . $insurance->insurance_document) }}" target="_blank">View Document</a>
                            @else
                                No document uploaded
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Back button -->
        <a href="{{ route('insurances.index') }}" class="btn btn-primary mt-3">Back</a>
    </div>
</x-app-layout>
