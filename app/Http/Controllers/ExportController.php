<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportPDF(Request $request)
    {
        $query = Insurance::query();

        if ($request->has('asset_name')) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('asset_name', 'like', '%' . $request->input('asset_name') . '%');
            });
        }

        if ($request->has('claim_number')) {
            $query->where('claim_number', 'like', '%' . $request->input('claim_number') . '%');
        }

        if ($request->has('status')) {
            $query->where('insurance_status', $request->input('status'));
        }

        $insurances = $query->get();

        $pdf = Pdf::loadView('insurance.report', compact('insurances'));
        return $pdf->download('insurance_report.pdf');
    }

    
}
