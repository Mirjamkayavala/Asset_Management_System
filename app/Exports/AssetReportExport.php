<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $location;

    public function __construct($startDate, $endDate, $location)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->location = $location;
    }

    // Fetch assets based on the passed parameters
    public function collection()
    {
        return Asset::whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->where('location', $this->location)
                    ->get();
    }

    // Define the column headers for the Excel file
    public function headings(): array
    {
        return ['Asset Name', 'Status', 'Location', 'Created At'];
    }

    // Define how each row will be mapped in the Excel file
    public function map($asset): array
    {
        return [
            $asset->name,
            $asset->status,
            $asset->location,
            $asset->created_at->format('Y-m-d'),
        ];
    }
}
