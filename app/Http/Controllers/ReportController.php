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
use App\Models\Vendor;
use App\Models\User;
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

    if ($request->filled('category')) {
        $assets->where('category', $request->category);
    }
    if ($request->filled('location')) {
        $assets->where('location', $request->location);
    }

    if ($request->filled('model')) {
        $assets->where('model', $request->model);
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

    // Fetch the filtered assets
    $assets = $assets->get(); // Use the same query builder to fetch results

    // Retrieve other necessary data
    $makes = Asset::select('make')->distinct()->get();
    $models = Asset::select('model')->distinct()->get();
    // $locations = Asset::select('location')->distinct()->get();
    $statuses = ['In use', 'New', 'Old', 'In Storage', 'Broken', 'Written Off'];
    $locations = Location::all();

    // Pass the filtered assets and other data to the view
    return view('reports.index', compact('assets', 'makes', 'models', 'locations', 'statuses'));
}



    private function getFilteredAssets(Request $request)
    {
        // Initialize the query for filtering assets
        $assets = Asset::query();
    
        // Debug the SQL query to see if it's being generated correctly
        // dd($query->toSql(), $query->getBindings()); 
        
    
        // Apply filters based on the request parameters
        if ($request->filled('status')) {
            $assets->where('status', $request->status);
        }
    
        if ($request->filled('asset_number')) {
            $assets->where('asset_number', $request->asset_number);
        }
    
        if ($request->filled('category')) {
            $assets->where('category', $request->category);
        }
        if ($request->filled('location')) {
            $assets->where('location', $request->location);
        }
    
        if ($request->filled('model')) {
            $assets->where('model', $request->model);
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
    
        // Get the filtered assets
        return $assets->get();
    }
    


    public function preview(Request $request)
    {
        $this->authorize('viewAny', Asset::class);
        $assets = $this->getFilteredAssets($request);

        return view('reports.preview', compact('assets'));
    }

    // public function exportPdf(Request $request)
    // {
    //     // Create query to filter assets
    //     $query = Asset::with(['assetCategories', 'users', 'previousUser', 'vendors']);

    //     // Apply filters from the request
    //     if ($request->has('serial_number') && $request->serial_number != '') {
    //         $query->where('serial_number', 'like', '%' . $request->serial_number . '%');
    //     }
    //     if ($request->has('asset_number') && $request->asset_number != '') {
    //         $query->where('asset_number', 'like', '%' . $request->asset_number . '%');
    //     }
    //     if ($request->has('location') && $request->location != '') {
    //         $query->where('location', $request->location);
    //     }
    //     if ($request->has('status') && $request->status != '') {
    //         $query->where('status', $request->status);
    //     }
    //     if ($request->has('start_date') && $request->has('end_date')) {
    //         $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    //     }

    //     // Get the filtered assets
    //     $assets = $query->get();

    //     // Set a timeout limit for large PDF generation
    //     set_time_limit(120);

    //     // Generate PDF with filtered assets
    //     $pdf = PDF::loadView('report.pdf', compact('assets'));

    //     // Return the generated PDF
    //     return $pdf->download('Asset_report.pdf');
    // }



    public function export(Request $request, $format)
    {
        // $assets = $this->getFilteredAssets($request);

        // Get filtered assets based on the request
        $assets = $this->getFilteredAssets($request);

        $query = Asset::query();

        // Example of filtering logic - adjust according to your filter inputs
        if ($request->has('make')) {
            $query->where('make', $request->input('make'));
        }
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Fetch the filtered assets
        $assets = $query->get();

        $assets = Asset::with(['assetCategories', 'users', 'previousUser', 'vendors'])->get();

        $headers = [
            'Make', 'Model', 'Serial Number', 'Asset Number','Category' ,'Current User',  'Date', 'Previous User','Vendor', 'Location','Status',
            
        ];

        switch ($format) {
            case 'pdf':
                $assetsArray = $assets->map(function ($asset) {
                    return [
                        'make' => $asset->make,
                        'model' => $asset->model,
                        'serial_number' => $asset->serial_number,
                        'asset_number' => $asset->asset_number,
                        'category' => $asset->category,
                        // 'category_name' => optional($asset->assetCategories)->category_name ?? 'N/A',
                        'user_name' => optional($asset->users)->name ?? 'N/A',
                        'date' => $asset->date,
                        'previousUser' => optional($asset->previousUser)->name ?? 'N/A',
                        'vendor' => $asset->vendor,
                        'location' => $asset->location,
                        // 'vendor_name' => optional($asset->vendors)->vendor_name ?? 'N/A',
                        'status' => $asset->status,
                    ];
                })->toArray();
                

                $pdf = Pdf::loadView('reports.pdf', ['assets' => $assetsArray])
                    ->setOptions(['defaultFont' => 'sans-serif'])
                    ->setPaper('a4', 'landscape');

                   
                $pdf->getDomPDF()->add_info('Title', 'Assets Report');
                // Add the logo to the PDF header
                $pdf->getDomPDF()->getCanvas()->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                    $imagePath = public_path('images/ceno-logo.png');
                    $canvas->image($imagePath, 10, 10, 100, 50);
                });

                
                // return view('reports.pdf', compact('assets'));
                return $pdf->download('assets_report.pdf');

            case 'excel':
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Add the logo
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('images/ceno-logo.png')); 
                $drawing->setHeight(5); // Height of the logo
                $drawing->setWidth(700); // Width of the logo 
                $drawing->setCoordinates('A1'); 
                $drawing->setWorksheet($sheet);

                $sheet->getRowDimension('1')->setRowHeight(40); 
                $sheet->mergeCells('A6:J6'); 
                $sheet->setCellValue('A6', 'Asset Report'); 
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
                
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Add space after the title before the headers
                $sheet->getRowDimension('2')->setRowHeight(40);

                $headers = ['Make', 'Model', 'Serial Number', 'Asset Number', 'Category','Current User','Date', 'Previous User',   'Vendor','Location', 'Status',];
                $headerRow = 7;
                // Define header row
                foreach ($headers as $key => $header) {
                    // $cell = chr(65 + $key) . '11'; // Starting row after logo (e.g., row 7)
                    $cell = chr(65 + $key) . $headerRow; 
                    $sheet->setCellValue($cell, $header);

                    // Make the header bold
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
            ]);
                }

                // Set asset data rows
                $row = $headerRow + 1;
                foreach ($assets as $asset) {
                    $sheet->setCellValue('A' . $row, $asset->make);
                    $sheet->setCellValue('B' . $row, $asset->model);
                    $sheet->setCellValue('C' . $row, $asset->serial_number);
                    $sheet->setCellValue('D' . $row, $asset->asset_number);
                    // $sheet->setCellValue('E' . $row, optional($asset->departments)->department_name ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $asset->category);
                    // $sheet->setCellValue('E' . $row, optional($asset->assetCategories)->category_name ?? 'N/A');
                    // $sheet->setCellValue('G' . $row, optional($asset->locations)->location_name ?? 'N/A');
                    $sheet->setCellValue('F' . $row, optional($asset->users)->name ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $asset->date);
                    $sheet->setCellValue('H' . $row, optional($asset->previousUser)->name ?? 'N/A');
                    // <td>{{ $asset->previousUser ? $asset->previousUser->name : 'N/A' }}</td>
                    $sheet->setCellValue('I' . $row, $asset->vendor);
                    $sheet->setCellValue('J' . $row, $asset->location);
                    // $sheet->setCellValue('I' . $row, optional($asset->vendors)->vendor_name ?? 'N/A');
                    $sheet->setCellValue('K' . $row, $asset->status);
                    
                    $row++;
                }

                $writer = new Xlsx($spreadsheet);
                $fileName = 'assets_report.xlsx';
                $writer->save(storage_path('app/' . $fileName));

                return response()->download(storage_path('app/' . $fileName))->deleteFileAfterSend(true);

            case 'word':
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();

                // Add the logo
                $section->addImage(public_path('images/ceno-logo.png'), [
                    'width' => 500,
                    'height' => 100,
                    'alignment' => 'center',
                ]);

                // Add a line break between the logo and the header
                $section->addTextBreak(1);

                // Add the header
                $section->addText('Assets Report', ['bold' => true, 'size' => 16], ['alignment' => 'center']);

                // Add table style
                $styleTable = ['borderSize' => 6, 'borderColor' => '999999'];
                $phpWord->addTableStyle('Asset Table', $styleTable);

                // Add table
                $table = $section->addTable('Asset Table');

                // Add header row
                $table->addRow();
                foreach ($headers as $header) {
                    // $table->addCell()->addText($header);
                    $table->addCell()->addText($header, ['bold' => true]);
                }

                 
                // Add asset data rows
                foreach ($assets as $asset) {
                    $table->addRow();
                    $table->addCell()->addText($asset->make);
                    $table->addCell()->addText($asset->model);
                    $table->addCell()->addText($asset->serial_number);
                    $table->addCell()->addText($asset->asset_number);
                    // $table->addCell()->addText(optional($asset->departments)->department_name ?? 'N/A');
                    $table->addCell()->addText($asset->category);
                    // $table->addCell()->addText(optional($asset->assetCategories)->category_name ?? 'N/A');
                    // $table->addCell()->addText(optional($asset->locations)->location_name ?? 'N/A');
                    $table->addCell()->addText(optional($asset->users)->name ?? 'N/A');
                    $table->addCell()->addText($asset->date);
                    $table->addCell()->addText(optional($asset->previousUser)->name ?? 'N/A');
                    // $table->addCell()->addText(optional($asset->previousUser)->name ?? 'N/A');
                    $table->addCell()->addText($asset->vendor);
                    // $table->addCell()->addText(optional($asset->vendors)->vendor_name ?? 'N/A');
                  
                    $table->addCell()->addText($asset->location);
                    $table->addCell()->addText($asset->status);
                    // $table->addCell()->addText($asset->warranty_expire_date);
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

   


     // Display asset requisition form
     public function showRequisitionForm($assetId)
     {
          $asset = Asset::with(['users', 'vendors'])->findOrFail($assetId);
     
          return view('reports.requisition', compact('asset'));
     }
     
     // Export the requisition form as PDF
     public function exportRequisitionFormToPdf($assetId)
     {
          $asset = Asset::with(['users', 'vendors'])->findOrFail($assetId);
     
          $pdf = Pdf::loadView('reports.requisition_pdf', compact('asset'))
                    ->setPaper('a4', 'portrait');
     
          return $pdf->download('Asset_Requisition_Form.pdf');
     }
     
}
