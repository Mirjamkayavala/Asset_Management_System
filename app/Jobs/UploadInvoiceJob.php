<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoice;

class UploadInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;
    protected $tempPath;

    public function __construct(Invoice $invoice, $tempPath)
    {
        $this->invoice = $invoice;
        $this->tempPath = $tempPath;
    }

    public function handle()
    {
        // Move the file from the temporary location to the final location
        $finalPath = Storage::disk('public')->move($this->tempPath, 'invoices/' . basename($this->tempPath));
        $this->invoice->file_path = $finalPath;
        $this->invoice->save();
    }
}
