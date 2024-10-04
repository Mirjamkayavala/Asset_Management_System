<x-app-layout>
    <h6>Invoices</h6>
    @can('create', App\Models\Invoice::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary btn-create" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                    Create New Invoice
                </button>
            </div>
        </div>
    </section>
    @endcan
    <br>
    <br>
    <br>

    <div class="container">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($invoices->isEmpty())
            <div class="alert alert-info">
                No invoices found.
            </div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice Number</th>
                        <th>Purchased Date</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->invoice_date }}</td>
                            <td>{{ $invoice->amount }}</td>
                            <td>
                                @can('view', $invoice)
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>  
                                </a>
                                @endcan

                                @can('edit', App\Models\Invoice::class)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInvoiceModal" 
                                    data-id="{{ $invoice->id }}"
                                    data-invoice_number="{{ $invoice->invoice_number }}"
                                    data-invoice_date="{{ $invoice->invoice_date }}"
                                    data-amount="{{ $invoice->amount }}"
                                    data-file_path="{{ $invoice->file_path }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan

                                @can('delete', $invoice)
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Purchased Date</label>
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ old('invoice_date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" value="{{ old('amount') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_path" class="form-label">Invoice File</label>
                            <input type="file" class="form-control" id="file_path" name="file_path" value="{{ old('file_path') }}" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInvoiceModalLabel">Edit Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editInvoiceForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_invoice_number" class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" id="edit_invoice_number" name="invoice_number" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_invoice_date" class="form-label">Purchased Date</label>
                            <input type="date" class="form-control" id="edit_invoice_date" name="invoice_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_file_path" class="form-label">Invoice File</label>
                            <input type="file" class="form-control" id="edit_file_path" name="file_path">
                            <small id="current_file" class="form-text text-muted"></small>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <!-- JavaScript for handling modal population -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var editInvoiceModal = document.getElementById('editInvoiceModal');
        editInvoiceModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var invoiceNumber = button.getAttribute('data-invoice_number');
            var invoiceDate = button.getAttribute('data-invoice_date');
            var amount = button.getAttribute('data-amount');
            var filePath = button.getAttribute('data-file_path');

            var form = document.getElementById('editInvoiceForm');
            form.action = '/invoices/' + id;
            form.querySelector('#edit_invoice_number').value = invoiceNumber;
            form.querySelector('#edit_invoice_date').value = invoiceDate;
            form.querySelector('#edit_amount').value = amount;

            var currentFileText = filePath ? `<a href="${filePath}" target="_blank">Current File</a>` : 'No file uploaded';
            form.querySelector('#current_file').innerHTML = currentFileText;
        });
    });

    </script>
</x-app-layout>
