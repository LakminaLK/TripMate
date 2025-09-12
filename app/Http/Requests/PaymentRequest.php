<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'booking_data' => 'required',
            'card_number' => 'required|string|min:16|max:19',
            'card_name' => 'required|string|max:255',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|min:4|max:4',
            'cvv' => 'required|string|size:3',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_postal' => 'required|string|max:20',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'booking_data.required' => 'Booking data is required.',
            'card_number.required' => 'Please enter your card number.',
            'card_number.min' => 'Card number must be at least 16 digits.',
            'card_number.max' => 'Card number cannot exceed 19 digits.',
            'card_name.required' => 'Please enter the cardholder name.',
            'expiry_month.required' => 'Please enter the expiry month.',
            'expiry_month.size' => 'Expiry month must be 2 digits.',
            'expiry_year.required' => 'Please enter the expiry year.',
            'cvv.required' => 'Please enter the CVV.',
            'cvv.size' => 'CVV must be 3 digits.',
            'billing_address.required' => 'Please enter your billing address.',
            'billing_city.required' => 'Please enter your billing city.',
            'billing_postal.required' => 'Please enter your postal code.',
        ];
    }

    /**
     * Get the booking data as an array.
     */
    public function getBookingData(): array
    {
        return json_decode($this->booking_data, true) ?? [];
    }

    /**
     * Get payment details for storage.
     */
    public function getPaymentDetails(): array
    {
        return [
            'card_last_four' => substr($this->card_number, -4),
            'card_name' => $this->card_name,
            'processed_at' => now(),
        ];
    }
}