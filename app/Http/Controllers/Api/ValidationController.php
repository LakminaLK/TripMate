<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ValidationController extends Controller
{
    /**
     * Check if email already exists in database
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = Tourist::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email already exists' : 'Email available'
        ]);
    }

    /**
     * Check if mobile number already exists in database
     */
    public function checkMobile(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => 'required|string'
        ]);

        $exists = Tourist::where('mobile', $request->mobile)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Mobile number already exists' : 'Mobile number available'
        ]);
    }
}
