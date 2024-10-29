<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceRequest extends FormRequest
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
            'asset_id' => 'required|exists:assets,id',
            'insurance_type' => 'required|string|max:255',
            'claim_number' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Approved,Claimed,Rejected',
            'claim_date' => 'nullable|date|before_or_equal:today',
            'approval_date' => 'nullable|date|before_or_equal:today',
            'rejection_date' => 'nullable|date|before_or_equal:today',
            'description' => 'required|string|max:255',
            'insurance_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'serial_number' => 'required|exists:assets,serial_number',
        ];
    }
}
