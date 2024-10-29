<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InsuranceReportController extends Controller
{
    public function index()
    {
        $insurances = Insurance::with(['asset', 'user'])->get();

        return view('insurance.report', compact('insurances'));
    }

    public function exportPdf()
    {   
        set_time_limit(120);
        $insurances = Insurance::all();
        $pdf = PDF::loadView('insurance.insurance_pdf', compact('insurances'));
        return $pdf->download('insurance report.pdf');
    }

    public function exportExcel()
    {
        $insurances = Insurance::with(['asset', 'user'])->get();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add the logo
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('images/ceno-logo.png')); 
        $drawing->setHeight(150); 
        $drawing->setCoordinates('A1'); 
        $drawing->setWorksheet($sheet);
    
       // Define headers with a line break (adjust the starting row number)
        $headers = ['ID', 'Make', 'Serial Number','Assign To', 'Policy Number', 'Amount', 'Company', 'Status', 'Comments'];
        $headerRow = 11; 

        // Set header row
        foreach ($headers as $key => $header) {
            $cell = chr(65 + $key) . $headerRow; 
            $sheet->setCellValue($cell, $header);
            
            // Make the header bold
            $sheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        // Set insurance data rows
        $row = $headerRow + 1; 
        foreach ($insurances as $insurance) {
            $sheet->setCellValue('A' . $row, $insurance->id);
            $sheet->setCellValue('B' . $row, optional($insurance->asset)->make ?? 'N/A');
            $sheet->setCellValue('C' . $row, optional($insurance->asset)->serial_number ?? 'N/A');
            $sheet->setCellValue('D' . $row, optional($insurance->user)->name ?? 'N/A');
            $sheet->setCellValue('E' . $row, $insurance->claim_number);
            $sheet->setCellValue('F' . $row, $insurance->amount);
            $sheet->setCellValue('G' . $row, $insurance->company);
            $sheet->setCellValue('H' . $row, ucfirst($insurance->status));
            $sheet->setCellValue('I' . $row, $insurance->description);
            $row++;
        }

    
        // Create a new Xlsx object and save the spreadsheet to a file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'insurance report.xlsx';
        $filePath = storage_path('app/' . $fileName);
        $writer->save($filePath);
    
        // Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    


    
    public function exportWord()
    {
        set_time_limit(120); // Increase execution time limit

        $insurances = Insurance::all();
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Set default font
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        // Add the logo
        $imagePath = public_path('images/ceno-logo.png'); // Get the absolute file path
        if (file_exists($imagePath)) {
            $section->addImage($imagePath, [
                'width' => 500,
                'height' => 100,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        } else {
            // Handle the case where the image file does not exist
            $section->addText('Logo image not found.', ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }

        // Add a line break between the logo and the header
        $section->addTextBreak(1);

        // Add the header
        $section->addText('Insurance Report', ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Add a table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);

        // Define the table headers
        $table->addRow();
        $table->addCell()->addText('ID', ['bold' => true]);
        $table->addCell()->addText('Make', ['bold' => true]);
        $table->addCell()->addText('Serial Number', ['bold' => true]);
        $table->addCell()->addText('Claimed By', ['bold' => true]);
        $table->addCell()->addText('Policy Number', ['bold' => true]);
        $table->addCell()->addText('Amount', ['bold' => true]);
        $table->addCell()->addText('Company', ['bold' => true]);
        $table->addCell()->addText('Status', ['bold' => true]);
        $table->addCell()->addText('Comments', ['bold' => true]);


        // Add the insurance data rows
        foreach ($insurances as $insurance) {
            $table->addRow();
            $table->addCell()->addText($insurance->id);
            $table->addCell()->addText($insurance->asset ? $insurance->asset->make : 'N/A');
            $table->addCell()->addText($insurance->asset ? $insurance->asset->serial_number : 'N/A');
            $table->addCell()->addText($insurance->user ? $insurance->user->name : 'N/A');
            $table->addCell()->addText($insurance->claim_number);
            $table->addCell()->addText($insurance->amount);
            $table->addCell()->addText($insurance->company);
            $table->addCell()->addText(ucfirst($insurance->status));
            $table->addCell()->addText($insurance->description);
        }

        // Save the Word document to a temporary file
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
        $writer->save($temp_file);

        // Return the file as a download response
        return response()->download($temp_file, 'insurance report.docx')->deleteFileAfterSend(true);
    }

    


    public function filter(Request $request)
    {
        $query = Insurance::query();

        if ($request->has('make')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('make', 'like', '%' . $request->make . '%');
            });
        }

        if ($request->has('claim_number')) {
            $query->where('claim_number', 'like', '%' . $request->claim_number . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $insurances = $query->get();

        // // Return a partial view or JSON response with the new table rows
        // return response()->json([
        //     'tableBody' => view('partials.insurance_table_body', compact('insurances'))->render(),
        // ]);

        return view('insurance.report', compact('insurances'));
    }

}
