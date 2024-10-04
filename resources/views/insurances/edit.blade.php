<x-app-layout>
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

    <h6>Update Data</h6>
    <form action="{{ route('insurances.update', $insurance->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                    
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="asset_id">Make</label>
                        <select name="asset_id" id="asset_id" class="form-control">
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ $asset->id == $insurance->asset_id ? 'selected' : '' }}>
                                    {{ $asset->make }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="claim_number">Policy Number</label>
                        <input type="text" name="claim_number" id="claim_number" class="form-control" value="{{ $insurance->claim_number }}">
                    </div>
                </div>
                    
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                    
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="insurance_type">Insurance Type</label>
                        <input type="text" name="insurance_type" id="insurance_type" class="form-control" value="{{ $insurance->insurance_type }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{ $insurance->amount }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                    
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id">Claimed By</label>
                        <select name="user_id" id="user_id" class="form-control">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $insurance->user_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Approved" {{ $insurance->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Claimed" {{ $insurance->status == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="Rejected" {{ $insurance->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="claim-date-section">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="claim_date">Claim Date</label>
                        <input type="date" name="claim_date" id="claim_date" class="form-control" value="{{ $insurance->claim_date }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="approval_date">Approval Date</label>
                        <input type="date" name="approval_date" id="approval_date" class="form-control" value="{{ $insurance->approval_date }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="rejection-date-section">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rejection_date">Rejection Date</label>
                        <input type="date" name="rejection_date" id="rejection_date" class="form-control" value="{{ $insurance->rejection_date }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="description">Comments</label>
                        <textarea name="description" id="description" class="form-control">{{ $insurance->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="insurance_document">Insurance Documents</label>
                        @if($insurance->insurance_document)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $insurance->insurance_document) }}" target="_blank">View current document</a>
                                <button type="button" class="btn btn-danger btn-sm" id="remove-document">Remove</button>
                            </div>
                        @endif
                        <input type="file" name="insurance_document" id="insurance_document" class="form-control">
                        <input type="hidden" name="existing_insurance_document" id="existing_insurance_document" value="{{ $insurance->insurance_document }}">
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('insurances.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script>
    function toggleFields() {
        const status = document.getElementById('status').value;
        const claimDateSection = document.getElementById('claim-date-section');
        const rejectionDateSection = document.getElementById('rejection-date-section');

        if (status === 'Claimed') {
            claimDateSection.style.display = 'block';
            rejectionDateSection.style.display = 'none';
        } else if (status === 'Rejected') {
            claimDateSection.style.display = 'none';
            rejectionDateSection.style.display = 'block';
        } else {
            claimDateSection.style.display = 'block';
            rejectionDateSection.style.display = 'block';
        }
    }

    document.getElementById('status').addEventListener('change', toggleFields);

    // Initial call to set the fields based on the loaded status
    toggleFields();
</script>

</x-app-layout>
