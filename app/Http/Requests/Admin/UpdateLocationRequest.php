<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $id = $this->route('location')->id;
    return [
        'name'         => ['required','string','max:255', Rule::unique('locations','name')->ignore($id)],
        'description'  => ['nullable','string'],
        'status'       => ['required','string','in:active,inactive'],
        'activities'   => ['required','array','min:1'],
        'activities.*' => ['integer','exists:activities,id'],

        'main_image'   => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        'gallery.*'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
    ];
}

}
