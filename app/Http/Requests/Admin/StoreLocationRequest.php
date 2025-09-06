<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gate via route middleware 'auth:admin'
    }

    public function rules(): array
{
    return [
        'name'         => ['required','string','max:255','unique:locations,name'],
        'description'  => ['nullable','string'],
        'status'       => ['required','string','in:active,inactive'],
        'activities'   => ['required','array','min:1'],
        'activities.*' => ['integer','exists:activities,id'],

        // images
        'main_image'   => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        'gallery.*'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
    ];
}

}
