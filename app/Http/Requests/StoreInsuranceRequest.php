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
            // Ensure the asset exists in the assets table
            'asset_id' => 'required|exists:assets,id',
            
            // Optional insurance type, must be a string with a max length of 255
            'insurance_type' => 'nullable|string|max:255',
            
            // Optional claim number, must be a string with a max length of 255
            'claim_number' => 'nullable|string|max:255',
            
            // Must be one of 'Internal' or 'External'
            'written_off_source' => 'nullable|string|in:Internal,External',
            
            // Required numeric value for the amount
            'amount' => 'required|numeric',
            
            // User who created the insurance request, must exist in the users table
            'user_id' => 'required|exists:users,id',
            
            // Last user who updated the request, must exist in the users table
            'last_user_id' => 'required|exists:users,id',
            
            // Status of the insurance, must be one of the specified values
            'status' => 'required|in:Approved,Claimed,Rejected',
            
            // Optional claim date, must be a date and not later than today
            'claim_date' => 'nullable|date|before_or_equal:today',
            
            // Optional approval date, must be a date and not later than today
            'approval_date' => 'nullable|date|before_or_equal:today',
            
            // Optional rejection date, must be a date and not later than today
            'rejection_date' => 'nullable|date|before_or_equal:today',
            
            // Description is required, must be a string with a max length of 255
            'description' => 'required|string|max:255',
            
            // Required file upload, must be a PDF, JPG, JPEG, or PNG with a max size of 2MB
            'insurance_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Optional serial number, must exist in the assets table under the 'serial_number' column
            'serial_number' => 'nullable|string|exists:assets,serial_number|max:255',
        ];
    }
}
