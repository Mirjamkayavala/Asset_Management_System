<x-app-layout>
    <div class="container">
        <h6>Create Insurance Claim</h6>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
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
                            <select class="form-control select2" name="asset_id" id="asset_id" class="form-control select2">
                                <option value="">Select a Make</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->make }}</option>
                                @endforeach
                            </select>
                            @error('make')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Serial Number -->
                        <div class="col-md-6 form-group">
                            <label for="serial_number">Asset Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number') }}" required>
                            @error('serial_number')
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
                            <label for="written_off_source">Written Off Source</label>
                            <select class="form-control select2" name="written_off_source" id="written_off_source" class="form-control" onchange="toggleInsuranceDocumentField()">
                                <option value="">Select Option</option>
                                <option value="Internal">Internal</option>
                                <option value="External">External</option>
                            </select>
                            @error('written_off_source')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="last_user_id">Last Person to Assigned Asset</label>
                            <select class="form-control select2" name="last_user_id" id="last_user_id" class="form-control select2" required>
                                <option value="">Select Last person to Use the Asset</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('last_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('last_user_id')
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
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="form-control" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror   
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select class="form-control select2" name="status" id="status" class="form-control">
                                <option value="Approved">Approved</option>
                                <option value="Pending">Pending</option>
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
                            <label for="insurance_document">Insurance Document</label>
                            <input type="file" name="insurance_document" id="insurance_document" class="form-control">
                            @error('insurance_document')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
            
                        <div class="form-group col-md-6">
                            <label for="user_id">Claimed By</label>
                            <select class= "form-control select2" name="user_id" id="user_id" class="form-control" required>
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
                            <label for="description">Comments</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                                    
                    </div>
                                    
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="claim_date">Claimed Date</label>
                            <input type="date" name="claim_date" id="claim_date" value="{{ old('claim_date') }}" class="form-control">
                            @error('claim_date')
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function toggleInsuranceDocumentField() {
            const writtenOffSource = document.getElementById('written_off_source').value;
            const insuranceDocumentField = document.getElementById('insurance_document').parentElement;

            if (writtenOffSource === 'External') {
                insuranceDocumentField.style.display = 'block';
            } else {
                insuranceDocumentField.style.display = 'none';
            }
        }

        // Call the function on page load to ensure the correct visibility is applied
        document.addEventListener('DOMContentLoaded', function() {
            toggleInsuranceDocumentField();
        });

        $(document).ready(function() {


            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
</x-app-layout>
