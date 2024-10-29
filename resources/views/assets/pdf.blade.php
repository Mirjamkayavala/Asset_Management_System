<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Control Form</title>
    <style>
        body {
            font-family: nexa;
            font-size: 16px;
        }
        .form-control {
            border: none;
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        h4 {
            text-align: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100%; /* Make the logo span the entire width */
            height: auto;
        }
        .print-hide {
            display: block;
        }
        @media print {
            .print-hide {
                display: none; /* Hide print/export buttons and separator when printing */
            }
        }
    </style>
</head>
<body>
    <!-- Logo Section -->
    <div class="logo">
        <img src="{{ asset('images/ceno.png') }}" alt="Company Logo"> <!-- Full-width logo -->
    </div>

    <!-- Form Content -->
    <div class="container-fluid">
        <h4>IT Control Form</h4>
        
        <div class="form-group row">
            <div class="col-md-4">
                <label for="issued_by">Issued By:</label>
                <p class="form-control">{{ $asset->issued_by }}</p>
            </div>
            <div class="col-md-4">
                <label for="received_by">Received By:</label>
                <p class="form-control">{{ $asset->received_by }}</p>
            </div>
            <div class="col-md-4">
                <label for="returned_by">Returned By:</label>
                <p class="form-control">{{ $asset->returned_by }}</p>
            </div>
        </div>

        <!-- Component Issued and Serial Number -->
        <div class="form-group row">
            <div class="col-md-6">
                <label for="component_issued">Component Issued:</label>
                <p class="form-control">{{ $asset->component_issued }}</p>
            </div>
            <div class="col-md-6">
                <label for="serial_number">Serial Number:</label>
                <p class="form-control"></p> <!-- Keeping this empty -->
            </div>
        </div>

        <!-- Fixed Asset Verification Number -->
        <div class="form-group">
            <label for="fixed_asset_verification_number">Fixed Asset Verification Number:</label>
            <p class="form-control">{{ $asset->fixed_asset_verification_number }}</p>
        </div>

        <!-- Declaration Section -->
        <div class="form-group">
            <label for="declaration">Declaration:</label>
            <p>I, ..................................... hereby acknowledge that I received the equipment in the following condition: {{ $asset->condition }}.</p>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="signature">Signature:</label>
                <p class="form-control">{{ $asset->user_signature }}</p>
            </div>
            <div class="col-md-4">
                <label for="date">Date:</label>
                <p class="form-control">{{ $asset->date_received }}</p>
            </div>
            <div class="col-md-4">
                <label for="witness">Witness:</label>
                <p class="form-control">{{ $asset->witness }}</p>
            </div>
        </div>

        <!-- IT Personnel Signature -->
        <div class="form-group">
            <label for="it_signature">Signature of IT Personnel:</label>
            <p class="form-control">{{ $asset->it_personnel_signature }}</p>
        </div>
        <br><br><br><br><br><br>
        <hr class="print-hide">
        
        <br><br><br><br><br><br><br><br><br>
        <!-- ICT Indemnity Form Section -->
        <h4>ICT Indemnity Form</h4><br>
        <p>1. I hereby declare that the ICT equipment issued is in good condition and that I will take full responsibility for any loss, damage, or misuse of this equipment.</p>
        <p>2. CENORED's equipments i.e. Laptops and Desktops are for official use only.</p>
        <p>3. The equipment can only be used by CENORED's employees according to CENORED's integrated ICT policy.</p>
        <p>4. Equipment i.e. Laptops, laptop adapters, laptop bags, etc, is the sole responsibility of the person who takes it beyond CENORED premises - theft and damage included.</p>
        <p>5. In case of theft or damage to the equipment, employees will be held liable to pay 30% replacement fee within the period of four months.</p>
        <p>I    ............................................. hereby acknowledge that I will be held liable to replace any equipment (Laptop, Adapter, Bag, etc) lost through theft or damaged.</p>

        <!-- Declaration and Signed At -->
        <div class="form-group row">
            <div class="col-md-6">
                <label for="signed_at">Signed at:</label>
                <p class="form-control">{{ $asset->signed_at }}</p>
            </div>
            <div class="col-md-6">
                <label for="date_signed">Date Signed:</label>
                <p class="form-control">{{ $asset->date_signed }}</p>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="signed_at">Signed By:</label>
                <p class="form-control">{{ $asset->signed_at }}</p>
            </div>
            <div class="col-md-6">
                <label for="date_signed">Date Signed:</label>
                <p class="form-control">{{ $asset->date_signed }}</p>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="signed_at">Witness:</label>
                <p class="form-control">{{ $asset->signed_at }}</p>
            </div>
            <div class="col-md-6">
                <label for="date_signed">Date Signed:</label>
                <p class="form-control">{{ $asset->date_signed }}</p>
            </div>
        </div>

        <div class="row print-hide">
            <!-- Download PDF Button -->
            
                <!-- <form action="{{ route('form.download', $asset->id) }}" method="GET">
                    <button type="submit" class="btn btn-success w-100" style="background-color: #04AA6D; border: 2px;">Download PDF</button>
                </form>
                <br>
             -->

            <!-- Print Button -->
            
            <button id="printButton" class="btn btn-primary w-100" style="background-color: #008CBA; border: 2px solid #000;">Print</button>

            

            <!-- Back Button -->
            
                <a href="{{ route('assets.index') }}" class="btn btn-secondary w-100" style="background-color: #008CBA; border: 2px solid #000;">Back</a>
            
        </div>
    </div>

    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            // Hide elements with the 'print-hide' class
            document.querySelectorAll('.print-hide').forEach(function (element) {
                element.style.display = 'none';
            });

            // Trigger the print
            window.print();

            // Restore hidden elements after printing
            document.querySelectorAll('.print-hide').forEach(function (element) {
                element.style.display = '';
            });
        });
    </script>
</body>
</html>
