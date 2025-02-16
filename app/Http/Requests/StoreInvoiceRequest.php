<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'required|unique:invoices',
            // 'asset_id' => 'required|exists:assets,id',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric',
            // 'status' => 'required|string',
            'file_path' => 'required|file|max:2048|mimes:pdf,jpg,jpeg,png',
        ];
    }
}
