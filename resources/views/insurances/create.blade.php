<x-app-layout>
    <div class="container">
        <!-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif -->
        <h6>Create Insurance Claim</h6>
        <form action="{{ route('insurances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('insurances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card mb-3">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="asset_id">Make</label>
                            <select name="asset_id" id="asset_id" class="form-control select2">
                                <option value="">Select an Make</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->make }}</option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="serial_number">Asset Serial Number</label>
                            <select name="serial_number" id="serial_number" class="form-control" required>
                                <option value="">Select Serial Number</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->serial_number }}">{{ $asset->serial_number }} - {{ $asset->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="insurance_type">Insurance Type</label>
                                <input type="text" name="insurance_type" id ="insurance_type" value="{{ old('insurance_type') }}" class ="form-control" required>
                                @error('insurance_type')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="claim_number">Policy Number</label>
                                <input type="text" name="claim_number" id ="claim_number" class ="form-control" value="{{ old('claim_number') }}" required>
                                @error('policy_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="form-control" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror   
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required onchange="toggleRejectedDate()">
                                <option value="Approved">Approved</option>
                                <option value="Approved">Pending</option>
                                <option value="Claimed">Claimed</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="user_id">Claimed By</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="approval_date">Approved Date</label>
                            <input type="date" name="approval_date" id="approval_date" value="{{ old('approval_date') }}" class="form-control">
                            @error('approval_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="rejection_date">Rejected Date</label>
                            <input type="date" name="rejection_date" id="rejection_date" value="{{ old('rejection_date') }}" class="form-control">
                            @error('rejection_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="claim_date">Claimed Date</label>
                            <input type="date" name="claim_date" id="claim_date" value="{{ old('claim_date') }}" class="form-control">
                            @error('claim_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="description">Comments</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="insurance_document">Insurance Documents</label>
                            <input type="file" name="insurance_document" id="insurance_document" class="form-control">
                            @error('insurance_document')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('insurances.index') }}" class="btn btn-secondary mr-2">Back</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br><br><br><br><br>

    <!-- <script>
        function toggleDateFields() {
            const status = document.getElementById('status').value;
            const approvalDate = document.getElementById('approval_date').parentElement;
            const rejectionDate = document.getElementById('rejection_date').parentElement;
            const claimDate = document.getElementById('claim_date').parentElement;

            if (status === 'Claimed' || status === 'Approved') {
                rejectionDate.style.display = 'none';
                approvalDate.style.display = 'block';
                claimDate.style.display = 'block';
            } else if (status === 'Rejected') {
                rejectionDate.style.display = 'block';
                approvalDate.style.display = 'none';
                claimDate.style.display = 'none';
            } else {
                rejectionDate.style.display = 'none';
                approvalDate.style.display = 'none';
                claimDate.style.display = 'none';
            }
        }

        // Call the function initially to set the correct visibility on page load
        document.addEventListener('DOMContentLoaded', toggleDateFields);
    </script> -->
</x-app-layout>
