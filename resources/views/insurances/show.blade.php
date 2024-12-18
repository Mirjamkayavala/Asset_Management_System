<x-app-layout>
    <div class="container">
        <h6>View Insurance Claim</h6>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Make</label>
                        <input type="text" class="form-control" value="{{ $insurance->asset->make ?? 'N/A' }}" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Asset Serial Number</label>
                        <input type="text" class="form-control" value="{{ $insurance->serial_number }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Written Off Source</label>
                        <input type="text" class="form-control" value="{{ $insurance->written_off_source }}" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Last Person Assigned the Asset</label>
                        <input type="text" class="form-control" value="{{ $insurance->lastUser->name ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control" value="{{ number_format($insurance->amount, 2) }}" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{ $insurance->status }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Insurance Document</label>
                        @if($insurance->insurance_document)
                            <input type="text" class="form-control" value="View Document" readonly>
                            <a href="{{ route('view.insurance.Document',$insurance->id)}}" target="_blank" class="btn btn-link">View Document</a>
                        @else
                            <input type="text" class="form-control" value="No Document Available" readonly>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Claimed By</label>
                        <input type="text" class="form-control" value="" readonly>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Comments</label>
                        <textarea class="form-control" rows="3" readonly>{{ $insurance->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('insurances.index') }}" class="btn btn-secondary">Back</a>

    </div>
</x-app-layout>
