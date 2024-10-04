<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Jobs\UploadInvoiceJob;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', $invoice);
        
        $assets = Asset::all();
        return view('invoices.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);

        $invoice = Invoice::create($request->validated());

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $tempPath = $file->store('temp', 'public');
            UploadInvoiceJob::dispatch($invoice, $tempPath);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('show', $invoice);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('edit', $invoice);
        $assets = Asset::all();
        return view('invoices.edit', compact('invoice', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $validated = $request->validated();
        $invoice->update($validated);

        if ($request->hasFile('file_path')) {
            // Delete the old file if it exists
            if ($invoice->file_path) {
                Storage::delete($invoice->file_path);
            }

            // Store the new file
            $file = $request->file('file_path');
            $filePath = $file->store('invoices'); // Store the file in the 'invoices' directory
            $invoice->file_path = $filePath; // Update the invoice with the new file path
            $invoice->save();

            // Dispatch the job with the correct file path
            UploadInvoiceJob::dispatch($invoice, $filePath);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        // Delete the file if exists
        if ($invoice->file_path) {
            Storage::delete($invoice->file_path);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function viewFile($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->file_path) {
            return Storage::download($invoice->file_path);
        } else {
            return redirect()->route('invoices.index')->with('error', 'No file found.');
        }
    }
}
