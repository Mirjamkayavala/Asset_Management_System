<x-app-layout>
    <h6>Insurance Details</h6>

    @can('create', App\Models\Insurance::class)
    <section class="container mt-5">
        <div class="card no-print">
            <div class="card-body text-left">
                <a href="{{ route('insurances.create') }}" class="btn btn-primary">Add New Insurance Claim</a>
            </div>
        </div>
    </section>
    @endcan

    <br><br><br>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($insurances->isEmpty())
        <div class="alert alert-info">
            No data available.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Make</th>
                    <!-- <th>Policy Number</th> -->
                    <!-- <th>Insurance Type</th> -->
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Claimed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($insurances as $insurance)
                    <tr>
                        <td>{{ $insurance->id }}</td>
                        <td>{{ $insurance->asset->make ?? 'N/A' }}</td>
                        <!-- <td>{{ $insurance->claim_number }}</td> -->
                        <!-- <td>{{ $insurance->insurance_type }}</td> -->
                        <td>{{ $insurance->amount }}</td>
                        <td>{{ $insurance->status }}</td>
                        <td>{{ $insurance->user->name ?? 'N/A' }}</td>
                        <td>
                            @can('view', $insurance)
                            <a href="{{ route('insurances.show', $insurance->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> 
                            </a>
                            @endcan

                            @can('edit', $insurance)
                            <a href="{{ route('insurances.edit', $insurance->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan

                            @can('delete', $insurance)
                            <form action="{{ route('insurances.destroy', $insurance->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this claim?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $insurances->links() }}
    @endif

    <br><br><br><br><br>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</x-app-layout>
