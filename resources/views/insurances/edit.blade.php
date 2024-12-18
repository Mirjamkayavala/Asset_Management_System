<x-app-layout>
    <div class="container">
        <h6>Edit Insurance Claim</h6>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('insurances.update', $insurance->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="asset_id">Make</label>
                            <select class= "form-control select2" name="asset_id" id="asset_id" class="form-control select2">
                                <option value="">Select a Make</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ $insurance->asset_id == $asset->id ? 'selected' : '' }}>{{ $asset->make }}</option>
                                @endforeach
                            </select>
                            @error('asset_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="serial_number">Asset Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $insurance->serial_number) }}" required>
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
                            <select class= "form-control select2" name="written_off_source" id="written_off_source" class="form-control" onchange="toggleInsuranceDocumentField()">
                                <option value="">Select Option</option>
                                <option value="Internal" {{ $insurance->written_off_source == 'Internal' ? 'selected' : '' }}>Internal</option>
                                <option value="External" {{ $insurance->written_off_source == 'External' ? 'selected' : '' }}>External</option>
                            </select>
                            @error('written_off_source')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="last_user_id">Last Person to Assigned Asset</label>
                            <select class= "form-control select2" name="last_user_id" id="last_user_id" class="form-control select2" required>
                                <option value="">Select Last person to Use the Asset</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $insurance->last_user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $insurance->amount) }}" class="form-control" required>
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror   
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select class= "form-control select2" name="status" id="status" class="form-control">
                                <option value="Approved" {{ $insurance->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Pending" {{ $insurance->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Claimed" {{ $insurance->status == 'Claimed' ? 'selected' : '' }}>Claimed</option>
                                <option value="Rejected" {{ $insurance->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
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
                        <div class="col-md-12 form-group">
                            <label for="insurance_document">Insurance Document</label>
                            <input type="file" name="insurance_document" id="insurance_document" class="form-control">
                            
                            @if($insurance->insurance_document)
                                <p>Current Document: 
                                    <a href="{{ route('view.insurance.Document',$insurance->id)}}" target="_blank">View Document</a>
                                </p>
                            @endif

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
                            <select class= "form-control select2" class="form-control select2" name="user_id" id="user_id" required>
                                <option value="">Select employee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $insurance->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="description">Comments</label>
                            <input type="text" name="description" id="description" value="{{ old('description', $insurance->description) }}" class="form-control">
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('insurances.index') }}" class="btn btn-secondary mr-2">Back</a>
            <button type="submit" class="btn btn-primary">Update</button>
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
