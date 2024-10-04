<x-app-layout>
    <style>
        /* Ensure the form covers the entire page */
        .container-fluid {
            width: 100%;
        }

        /* Make the image cover the top of the form */
        .form-logo {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Hide Navbar, Sidebar, and Print Button when printing */
        @media print {
            nav, .sidebar, .print-hide {
                display: none;
            }

            /* Move content to the left and adjust the width */
            .container-fluid {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            /* Make form content aligned to the left */
            .container-fluid .form-group, 
            .container-fluid h4, 
            .container-fluid p {
                text-align: left;
            }

            /* Remove default margin and padding from body */
            body {
                margin: 0;
                padding: 0;
            }

            /* Remove margins around the form */
            .container-fluid {
                margin-left: 0;
            }
        }
    </style>

    <div class="container-fluid mt-5">
        <!-- Logo covering the entire top of the form -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/ceno.png') }}" alt="Company Logo" class="form-logo">
        </div>

        <!-- Form Title -->
        <h4 class="text-center mb-4">IT Control Form</h4>

        <!-- Issued, Received, and Returned By -->
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

        <!-- Signatures and Dates -->
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

        <!-- ICT Indemnity Form Section -->
        <h6 style="margin-top: 180px;">ICT Indemnity Form</h6>
        <p>1. I hereby declare that the ICT equipment issued is in good condition and that I will take full responsibility for any loss, damage, or misuse of this equipment.</p>
        <p>2. CENORED's equipments i.e. Laptops and Desktops are for official use only.</p>
        <p>3. The equipment can only be used by CENORED's employees according to CENORED's integrated ICT policy.</p>
        <p>4. Equipment i.e. Laptops, laptop adapters, laptop bags, etc, is the sole responsibility of the person who takes it beyond CENORED premises - theft and damage included.</p>
        <p>5. In case of theft or damage to the equipment, employees will be held liable to pay 30% replacement fee within the period of four months.</p><br><br>
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

        <!-- Print and Export Buttons -->
        <div class="d-flex justify-content-between">
            <a href="#" id="printButton" class="btn btn-primary print-hide">Print</a>
            <!-- <a href="{{ route('assets.exportToPdf', $asset->id) }}" class="btn btn-secondary">Export to PDF</a> -->
        </div>

    </div>

    <br><br><br><br><br>
    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            // Hide elements with the class 'print-hide'
            document.querySelectorAll('.print-hide, .sidebar, nav').forEach(function (element) {
                element.style.display = 'none';
            });

            // Print the page
            window.print();

            // Show the hidden elements again after printing
            document.querySelectorAll('.print-hide, .sidebar, nav').forEach(function (element) {
                element.style.display = '';
            });
        });
    </script>
</x-app-layout>
