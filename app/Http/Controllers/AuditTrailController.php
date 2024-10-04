<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    // Display the list of audit trails
    public function index()
    {
        $auditTrails = AuditTrail::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('audit-trails.index', compact('auditTrails'));
    }

    public function clear()
    {
        // $this->authorize('delete', AssetHistory::class);
        AuditTrail::query()->delete();

        return redirect()->route('audit-trails.index')->with('success', 'Asset history cleared successfully.');
    }

}
