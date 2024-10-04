<x-app-layout>

<div class="container">
    <h2>Asset Requisition Form</h2>
    <p><strong>Make:</strong> {{ $asset->make }}</p>
    <p><strong>Model:</strong> {{ $asset->make }} {{ $asset->make }}</p>
    <p><strong>Issued By:</strong> {{ optional($asset->users)->name ?? 'N/A' }}</p>
    <p><strong>Received By:</strong> {{ optional($asset->users)->name ?? 'N/A' }}</p>
    <p><strong>Asset Number:</strong> {{$asset->asset_number}}</p>
    <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p><br><br>
    <!-- <p><strong>Declaration:</strong> {{ now()->format('Y-m-d') }}</p> -->

    <hr>

    <h4>Signatures</h4>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Received By:</strong></p>
            <p>Signature: ___________________________</p>
            <p>Date: ______________________________</p>
        </div>
        <div class="col-md-6">
            <p><strong>Signature of the IT Personnel:</strong></p>
            <p>Signature: ___________________________</p>
            <p>Date: ______________________________</p>
        </div>
    </div>

    <hr>

    <!-- Button to export form to PDF -->
    <a href="{{ route('requisition.export.pdf', $asset->id) }}" class="btn btn-primary">Export to PDF</a>

</div>


</x-app-layout>