<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        // $this->authorize('create assets');
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
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:55',
            'category' => 'nullable|string|max:55',
            'vendor' => 'nullable|string|max:55',
            'location' => 'nullable|string|max:55',
            'user_id' => 'nullable|exists:users,id',
            'manual_current_user' => 'nullable|string|max:255', 
            'asset_number' => 'nullable|string|unique:assets|max:25',
            'date' => 'required|date',
            'previous_user_id' => 'nullable|exists:users,id',
            'manual_previous_user' => 'nullable|string|max:255', 
            'category_id' => 'nullable|exists:asset_categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'insurance_id' => 'nullable|exists:insurances,id',
            'status' => 'nullable|string|max:25',
            
        ];
    }
}
