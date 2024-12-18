<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Control and ICT Indemnity Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .form-group label {
            flex: 0 0 200px; /* Fixed width for labels */
            text-align: left;
            margin-right: 10px;
        }
        .form-group .form-control {
            flex: 1; /* Input/line takes the remaining space */
            border: none;
            border-bottom: 1px solid #000;
            padding: 2px 5px;
            margin: 0;
        }
        h4 {
            text-align: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100%;
            height: auto;
        }
        .print-hide {
            display: block;
        }
        @media print {
            .print-hide {
                display: none;
            }
            .page-break {
                page-break-before: always;
            }
        }
        .form-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: calc(100% - 220px);
        }
        .signature-group {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .signature-group div {
            width: 48%;
        }
    </style>
</head>
<body>
    <!-- Logo Section -->
    <div class="logo">
        <img src="{{ asset('images/ceno.png') }}" alt="Company Logo">
    </div>

    <!-- IT Control Form Section -->
    <div>
        <h4>IT Control Form</h4>
        <div class="form-group">
            <label for="issued_by">Issued By:</label>
            <!-- <p class="form-control">{{ $asset->issued_by }}</p> -->
            <span class="form-line"></span>
        </div>
        <br>
        <div class="form-group">
            <label for="received_by">Received By:</label>
            <p class="form-control">{{ $asset->received_by }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="returned_by">Returned By:</label>
            <p class="form-control">{{ $asset->returned_by }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="component_issued">Component Issued:</label>
            <p class="form-control">{{ $asset->component_issued }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="serial_number">Serial Number:</label>
            <p class="form-control"></p>
        </div>
        <br>
        <div class="form-group">
            <label for="fixed_asset_verification_number">Fixed Asset Verification Number:</label>
            <p class="form-control">{{ $asset->fixed_asset_verification_number }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="declaration">Declaration:</label>
            <p>I, __________________________ hereby acknowledge that I received the  equipment in the following condition:<br> {{ $asset->condition }} ____________________________________________________________________</p>
            
        </div>
        <br>
        <div class="form-group">
            <label for="signature">Signature:</label>
            <p class="form-control">{{ $asset->user_signature }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="date">Date:</label>
            <p class="form-control">{{ $asset->date_received }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="witness">Witness:</label>
            <p class="form-control">{{ $asset->witness }}</p>
        </div>
        <br>
        <div class="form-group">
            <label for="it_signature">Signature of IT Personnel:</label>
            <span class="form-line"></span>
            <!-- <p class="form-control">{{ $asset->it_personnel_signature }}</p> -->
        </div>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- ICT Indemnity Form Section -->
    <div>
        <h4>ICT Indemnity Form</h4><br>
        <p>1. CENORED’s equipment i.e. Laptops and Desktops are for official use only</p><br>
        <p>2. The equipment can only be used by CENORED’s employees according to CENORED’s integrated ICT policy.</p><br>
        <!-- <p>3. The equipment can only be used by CENORED's employees according to CENORED's integrated ICT policy.</p> -->
        <p>3. Equipment, i.e., Laptops, laptop adapters, laptop bags, etc., is the sole responsibility of the person who takes it beyond CENORED premises - theft and damage included.</p><br>
        <p>4. In case of theft or damage to the equipment, employees will be held liable to pay 30% replacement fee within four months.</p><br>
        <p>I __________________________  hereby acknowledge that I will be held liable to replace any equipment  (Laptop, Adapter, Bag, etc.) lost through theft or damaged.</p><br>
        <div class="form-group">
            
            <p>Date/Signed at __________________________ on this ______ day of ______________20____.</p>
        </div>
        <br>
        <!-- <div class="form-group">
            <label for="date_signed">Date Signed:</label>
            <p class="form-control">{{ $asset->date_signed }}</p>
        </div> -->
        <br>
        <div class="signature-group">
            <div>
                <label>Signed By: </label>
                <span class="form-line"></span>
            </div>
            <div>
                <label>Date:</label>
                <span class="form-line"></span>
            </div>
        </div>
        <div class="signature-group">
            <div>
                <label>Witness:</label>
                <span class="form-line"></span>
            </div>
            <div>
                <label>Date:</label>
                <span class="form-line"></span>
            </div>
        </div>
    </div>

    <div class="row print-hide">
        <button id="printButton" class="btn btn-primary w-100" style="background-color: #008CBA; border: 2px solid #000;">Print</button>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary w-100" style="background-color: #008CBA; border: 2px solid #000;">Back</a>
    </div>

    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            document.querySelectorAll('.print-hide').forEach(function (element) {
                element.style.display = 'none';
            });
            window.print();
            document.querySelectorAll('.print-hide').forEach(function (element) {
                element.style.display = '';
            });
        });
    </script>
</body>
</html>
