<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tourist;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'last_month');
        
        // Get real counts
        $totalHotels = Hotel::count();
        $totalBookings = Booking::count();
        $totalActivities = Activity::count();
        $totalLocations = Location::count();
        $totalCustomers = Tourist::count();
        
        // Calculate last 30 days revenue (admin commission 10%)
        $last30DaysRevenue = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount') * 0.10; // 10% commission
            
        // Get revenue data based on period
        $revenueData = $this->getRevenueData($period);
        
        return view('admin.dashboard', compact(
            'totalHotels',
            'totalBookings', 
            'totalActivities',
            'totalLocations',
            'totalCustomers',
            'last30DaysRevenue',
            'revenueData',
            'period'
        ));
    }
    
    private function getRevenueData($period)
    {
        $query = Booking::where('status', '!=', 'cancelled');
        
        switch ($period) {
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::now();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth();
                $endDate = Carbon::now();
                break;
            case 'last_3_months':
                $startDate = Carbon::now()->subMonths(3);
                $endDate = Carbon::now();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear();
                $endDate = Carbon::now();
                break;
            default:
                $startDate = Carbon::now()->subMonth();
                $endDate = Carbon::now();
        }
        
        // Get daily revenue data for chart
        $dailyRevenue = $query->clone()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount * 0.10) as commission, COUNT(*) as booking_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Fill missing dates with zero values
        $dateRange = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $existing = $dailyRevenue->firstWhere('date', $dateStr);
            
            $dateRange[] = (object) [
                'date' => $dateStr,
                'commission' => $existing ? $existing->commission : 0,
                'booking_count' => $existing ? $existing->booking_count : 0
            ];
            
            $current->addDay();
        }
        
        return $dateRange;
    }

    public function customers()
    {
        $customers = Tourist::all(); // adjust if you use soft deletes or statuses
        return view('admin.customers', compact('customers'));
    }
}


