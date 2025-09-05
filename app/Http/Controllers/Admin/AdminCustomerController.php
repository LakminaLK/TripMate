<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        
        $query = Tourist::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%");
            });
        }

        $tourists = $query->orderBy('id')->paginate(10)->withQueryString();

        $customers = $tourists->getCollection()->map(function ($tourist) {
            return (object)[
                'id' => $tourist->id,
                'customer_id' => 'C' . str_pad($tourist->id, 3, '0', STR_PAD_LEFT),
                'name' => $tourist->name,
                'email' => $tourist->email,
                'mobile' => $tourist->mobile ?? 'N/A',
                'location' => $tourist->location ?: 'Unknown',
                'bookings_count' => '--',
                'total_spent' => 'Rs. 0.00'
            ];
        });

        // Replace the collection in the paginator
        $tourists->setCollection($customers);

        return view('admin.customers', ['customers' => $tourists]);
    }

    public function destroy($id)
{
    $customer = Tourist::findOrFail($id); // Replace with your model
    $customer->delete();

    return redirect()->route('admin.customers')->with('success', 'Customer deleted successfully.');
}

}
