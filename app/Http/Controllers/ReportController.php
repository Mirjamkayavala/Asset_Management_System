<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\AssetCategory;
use App\Models\Location;
use App\Models\Department;
use App\Models\Facility;

use App\Models\Vendor;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Invoice;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Start a query builder for assets
        $assets = Asset::query();
    
        // Apply filters based on the request parameters
        if ($request->filled('status')) {
            $assets->where('status', $request->status);
        }

        if ($request->filled('asset_number')) {
            $assets->where('asset_number', $request->asset_number);
        }

        if ($request->filled('category_id')) {
            $assets->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $assets->where('location_id', $request->location_id); 
        }

        if ($request->filled('model')) {
            $assets->where('model', $request->model);
        }

        if ($request->filled('make')) {
            $assets->where('make', $request->make);
        }
        if ($request->filled('facility_id')) {
            $assets->where('facility_id', $request->facility_id);
        }

        if ($request->filled('vendor')) {
            $assets->where('vendor', $request->vendor);
        }

        if ($request->has('user_id') && $request->user_id !== null) {
            $assets->where('user_id', $request->user_id);
        }

        // Add the date range filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = Carbon::parse($request->end_date)->endOfDay(); // Include the full end day
            $assets->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Paginate the filtered assets (10 assets per page)
        $assets = $assets->paginate(10); // You can change the number '10' to any value you prefer

        // Pass additional data for the filter options (if needed)
        $makes = Asset::select('make')->distinct()->get();
        $models = Asset::select('model')->distinct()->get();
        $locations = Location::all();
        $vendors = Vendor::all();
        $users = User::all();
        $facilities = Facility::all();
        $assetCategories = AssetCategory::all();

        return view('reports.index', compact('assets', 'makes', 'models', 'locations', 'vendors', 'users', 'facilities', 'assetCategories'));
    }

    



    private function getFilteredAssets(Request $request)
    {
        // Initialize the query for filtering assets
        $assets = Asset::query();

        // Debug the SQL query to see if it's being generated correctly
        // dd($assets->toSql(), $assets->getBindings()); // Updated to use $assets
        
        // Apply filters based on the request parameters
        if ($request->filled('status')) {
            $assets->where('status', $request->status);
        }

        if ($request->filled('asset_number')) {
            $assets->where('asset_number', $request->asset_number);
        }

        if ($request->filled('category_id')) {
            $assets->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $assets->where('location_id', $request->location_id); // Corrected to use $assets
        }

        if ($request->filled('model')) {
            $assets->where('model', $request->model);
        }
        if ($request->filled('facility_id')) {
            $assets->where('facility_id', $request->facility_id);
        }

        if ($request->filled('make')) {
            $assets->where('make', $request->make);
        }

        if ($request->filled('vendor')) {
            $assets->where('vendor', $request->vendor);
        }

        if ($request->filled('user_id')) {
            $assets->where('user_id', $request->user_id);
        }

        // Add the date range filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $assets->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Get the filtered assets
        return $assets->get();
    }

        

    public function preview(Request $request)
    {
        $this->authorize('viewAny', Asset::class);
        $assets = Asset::query()
            ->when($request->filled('filter'), function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->filter . '%');
            })
        ->paginate(10); 

        // dd(request()->query());
        return view('reports.preview', compact('assets'));
    }

    

    public function export(Request $request, $format)
    {
        // Apply the filtering logic
        $assets = $this->getFilteredAssets($request);  // Reuse the filtering logic
        // Headers for the report
        $headers = [
            'Make', 'Model', 'Serial Number', 'Asset Number', 'Category', 'Current User', 'Date', 'Vendor', 'Facility Space', 'Location', 'Status',
        ];

        switch ($format) {
            case 'pdf':
                $assetsArray = $assets->map(function ($asset) {
                    return [
                        'make' => $asset->make,
                        'model' => $asset->model,
                        'serial_number' => $asset->serial_number,
                        'asset_number' => $asset->asset_number,
                        'category_name' => optional($asset->assetCategory)->category_name ?? 'N/A',
                        'user_name' => optional($asset->user)->name ?? 'N/A',
                        'date' => $asset->date,
                        'vendor' => $asset->vendor,
                        'facility' => optional($asset->facility)->facility_name ?? 'N/A',
                        'location_name' => optional($asset->locations)->location_name ?? 'N/A',
                        'status' => $asset->status,
                    ];
                })->toArray();

                $assets = $this->getFilteredAssets($request);

                $pdf = Pdf::loadView('reports.pdf', ['assets' => $assetsArray])
                    ->setOptions(['defaultFont' => 'sans-serif'])
                    ->setPaper('a4', 'landscape');

                return $pdf->download('assets_report.pdf');

                case 'excel':
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                
                    // Add logo
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Logo');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(public_path('images/ceno-logo.png'));
                    $drawing->setHeight(4);
                    $drawing->setWidth(700);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                
                    $sheet->getRowDimension('1')->setRowHeight(40);
                    $sheet->mergeCells('A8:J8');
                    $sheet->setCellValue('A8', 'Asset Report');
                    $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(14);
                    $sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                
                    // Add headers
                    $headerRow = 9;
                    foreach ($headers as $key => $header) {
                        $cell = chr(65 + $key) . $headerRow;
                        $sheet->setCellValue($cell, $header);
                        $sheet->getStyle($cell)->applyFromArray(['font' => ['bold' => true]]);
                    }
                
                    // Add asset data rows
                    $row = $headerRow + 1;
                    foreach ($assets as $asset) {
                        $sheet->setCellValue('A' . $row, $asset->make);
                        $sheet->setCellValue('B' . $row, $asset->model);
                        $sheet->setCellValue('C' . $row, $asset->serial_number);
                        $sheet->setCellValue('D' . $row, $asset->asset_number);
                        $sheet->setCellValue('E' . $row, optional($asset->assetCategory)->category_name ?? 'N/A');
                        $sheet->setCellValue('F' . $row, optional($asset->user)->name ?? 'N/A');
                        $sheet->setCellValue('G' . $row, \Carbon\Carbon::parse($asset->date)->format('Y-m-d H:i:s'));
                        $sheet->setCellValue('H' . $row, $asset->vendor);
                        $sheet->setCellValue('I' . $row, optional($asset->facilities)->facility_name ?? 'N/A');
                        $sheet->setCellValue('J' . $row, optional($asset->locations)->location_name ?? 'N/A');
                        // $sheet->setCellValue('J' . $row, optional($asset->locations)->location_name ?? 'N/A');
                        $sheet->setCellValue('K' . $row, $asset->status);
                        $row++;
                    }
                
                    // Add total number of assets at the bottom
                    $totalAssets = count($assets); // Count total assets
                    $totalRow = $row + 1; // Leave an empty row after the data
                
                    $sheet->mergeCells("A{$totalRow}:J{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", "Total Assets: $totalAs sets");
                    $sheet->getStyle("A{$totalRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("A{$totalRow}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                
                    $assets = $this->getFilteredAssets($request);
                
                    $writer = new Xlsx($spreadsheet);
                    $fileName = 'assets_report.xlsx';
                    $writer->save(storage_path('app/' . $fileName));
                
                    return response()->download(storage_path('app/' . $fileName))->deleteFileAfterSend(true);
                
            case 'word':
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                // Add logo
                $section->addImage(public_path('images/ceno-logo.png'), [
                    'width' => 500,
                    'height' => 100,
                    'alignment' => 'center',
                ]);

                // Add header
                $section->addText('Assets Report', ['bold' => true, 'size' => 16], ['alignment' => 'center']);

                // Add table
                $styleTable = ['borderSize' => 6, 'borderColor' => '999999'];
                $phpWord->addTableStyle('Asset Table', $styleTable);
                $table = $section->addTable('Asset Table');

                $assets = $this->getFilteredAssets($request);

                // Add header row
                $table->addRow();
                foreach ($headers as $header) {
                    $table->addCell()->addText($header, ['bold' => true]);
                }

                // Add asset data rows
                foreach ($assets as $asset) {
                    $table->addRow();
                    $table->addCell()->addText($asset->make);
                    $table->addCell()->addText($asset->model);
                    $table->addCell()->addText($asset->serial_number);
                    $table->addCell()->addText($asset->asset_number);
                    $table->addCell()->addText(optional($asset->assetCategory)->category_name ?? 'N/A');
                    $table->addCell()->addText(optional($asset->user)->name ?? 'N/A');
                    // $table->addCell()->addText(\Carbon\Carbon::parse($asset->date)->format('Y-m-d H:i:s'));
                    $table->addCell()->addText(\Carbon\Carbon::parse($asset->created_at)->format('Y-m-d H:i:s'));

                    $table->addCell()->addText($asset->vendor);
                    $table->addCell()->addText(optinal($asset->facility)->facility_name ?? 'N/A');
                    $table->addCell()->addText(optional($asset->locations)->location_name ?? 'N/A');
                    $table->addCell()->addText($asset->status);
                }

                $writer = IOFactory::createWriter($phpWord, 'Word2007');
                $fileName = 'assets_report.docx';
                $writer->save(storage_path('app/' . $fileName));

                return response()->download(storage_path('app/' . $fileName))->deleteFileAfterSend(true);

            default:
                return redirect()->back();
        }
    }



    public function showAssetsReport()
    {
        $assets = Asset::with(['assetCategories', 'users', 'vendors'])->get();
        return view('reports.pdf', compact('assets'));
    }

   


     
}
