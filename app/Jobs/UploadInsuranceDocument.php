<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Insurance;

class UploadInsuranceDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $insuranceId;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param int $insuranceId
     */
    public function __construct($filePath, $insuranceId)
    {
        $this->filePath = $filePath;
        $this->insuranceId = $insuranceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $insurance = Insurance::find($this->insuranceId);
        $insurance->insurance_documents = $this->filePath;
        $insurance->save();
    }
}
