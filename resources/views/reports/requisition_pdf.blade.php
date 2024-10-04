

<!DOCTYPE html>
<html>
<head>
    <title>Asset Requisition Form</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .signature-box { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Asset Requisition Form</h2>
        </div>

        <p><strong>Make:</strong> {{ $asset->make }}</p>
        <p><strong>Model:</strong> {{ $asset->model }}</p>
        <p><strong>Asset Number:</strong> {{ $asset->make }} {{ $asset->model }}</p>
        <p><strong>Issued By:</strong> {{ optional($asset->users)->name ?? 'N/A' }}</p>
        <p><strong>Received By:</strong> {{ optional($asset->users)->name ?? 'N/A' }}</p>
        <p><strong>Vendor:</strong> {{ optional($asset->vendors)->vendor_name ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ now()->format('Y-m-d') }}</p>

        <hr>

        <h4>Signatures</h4>
        <div class="signature-box">
            <p><strong>Person Assigning the Asset:</strong></p>
            <p>Signature: ___________________________</p>
            <p>Date: ______________________________</p>
        </div>

        <div class="signature-box">
            <p><strong>Person Receiving the Asset:</strong></p>
            <p>Signature: ___________________________</p>
            <p>Date: ______________________________</p>
        </div>
    </div>
</body>
</html>
