<x-app-layout>
    <h6>Invoice Details</h6>
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-right">
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </section>
    <br>
    <br>
    <br>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Invoice Number</th>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Invoice Date</th>
                        <td>{{ $invoice->invoice_date }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $invoice->amount }}</td>
                    </tr>
                    <tr>
                        <th>Invoice File</th>
                        <td>
                            @if ($invoice->file_path)
                                <a href="{{ route('invoices.viewFile', $invoice->id) }}" target="_blank">View Invoice File</a>
                            @else
                                No file uploaded
                            @endif
                        </td>
                    </tr>
                </table>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInvoiceModal"
                    data-id="{{ $invoice->id }}"
                    data-invoice_number="{{ $invoice->invoice_number }}"
                    data-invoice_date="{{ $invoice->invoice_date }}"
                    data-amount="{{ $invoice->amount }}"
                    data-file_path="{{ $invoice->file_path }}">
                    Edit Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
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
                            <label for="edit_invoice_date" class="form-label">Invoice Date</label>
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

                var currentFileText = filePath ? `<a href="/invoices/${id}/file" target="_blank">Current File</a>` : 'No file uploaded';
                form.querySelector('#current_file').innerHTML = currentFileText;
            });
        });
    </script>
</x-app-layout>
