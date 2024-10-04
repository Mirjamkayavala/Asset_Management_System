<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssetCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $asset;

    public function __construct($asset)
    {
        $this->asset = $asset;
    }

    public function build()
    {
        return $this->subject('New Asset Added')
                    ->markdown('emails.asset_created')
                    ->with([
                        'assetNumber' => $this->asset->asset_number ?? 'N/A',
                        'assetName' => $this->asset->asset_name ?? 'N/A',
                        'serialNumber' => $this->asset->serial_number ?? 'N/A',
                        'description' => $this->asset->description ?? 'N/A',
                        'categoryId' => $this->asset->category_id ?? 'N/A',
                        'departmentId' => $this->asset->department_id ?? 'N/A',
                        'vendorId' => $this->asset->vendor_id ?? 'N/A',
                        'locationId' => $this->asset->location_id ?? 'N/A',
                        'userId' => $this->asset->user_id ?? 'N/A',
                        'invoiceNumber' => $this->asset->invoice_number ?? 'N/A',
                        'purchasedDate' => $this->asset->purchased_date ?? 'N/A',
                        'costPrice' => $this->asset->cost_price ?? 'N/A',
                        'status' => $this->asset->status ?? 'N/A',
                        'warrantyExpireDate' => $this->asset->warranty_expire_date ?? 'N/A',
                        'invoice' => $this->asset->invoice ?? 'N/A',
                    ]);
    }
}
