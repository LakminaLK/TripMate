<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRevenueController extends Controller
{
    private const ADMIN_COMMISSION_RATE = 0.10; // 10%

    /**
     * Display admin revenue dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period);

        // Get ALL bookings within date range (temporarily remove status filter)
        $bookings = Booking::with(['hotel', 'tourist', 'roomType'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();

        // Calculate revenue metrics
        $totalBookings = $bookings->count();
        $totalBookingValue = $bookings->sum('total_amount');
        $totalCommission = $totalBookingValue * self::ADMIN_COMMISSION_RATE;

        // Revenue by month for chart (last 12 months regardless of period)
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Top performing hotels by commission
        $topHotels = $this->getTopHotelsByCommission($dateRange);

        // Revenue breakdown by hotel
        $hotelRevenue = $this->getHotelRevenueBreakdown($dateRange);

        // Daily revenue for last 7 days
        $dailyRevenue = $this->getDailyRevenueForChart();
        
        // Daily revenue for selected period (for table)
        $dailyRevenueTable = $this->getDailyRevenue($dateRange);

        return view('admin.revenue.index', compact(
            'totalBookings',
            'totalBookingValue', 
            'totalCommission',
            'monthlyRevenue',
            'topHotels',
            'hotelRevenue',
            'dailyRevenue',
            'dailyRevenueTable',
            'period'
        ));
    }

    /**
     * Get detailed revenue report
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $bookings = Booking::with(['hotel', 'tourist', 'roomType'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function($query) {
                $query->whereIn('status', ['confirmed', 'completed'])
                      ->orWhereIn('booking_status', ['confirmed', 'completed']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add commission calculation to each booking
        $bookings->getCollection()->transform(function ($booking) {
            $booking->admin_commission = $booking->total_amount * self::ADMIN_COMMISSION_RATE;
            $booking->hotel_revenue = $booking->total_amount * (1 - self::ADMIN_COMMISSION_RATE);
            return $booking;
        });

        $summary = [
            'total_bookings' => $bookings->total(),
            'total_booking_value' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->where(function($query) {
                    $query->whereIn('status', ['confirmed', 'completed'])
                          ->orWhereIn('booking_status', ['confirmed', 'completed']);
                })
                ->sum('total_amount'),
            'total_commission' => 0,
            'total_hotel_revenue' => 0
        ];

        $summary['total_commission'] = $summary['total_booking_value'] * self::ADMIN_COMMISSION_RATE;
        $summary['total_hotel_revenue'] = $summary['total_booking_value'] * (1 - self::ADMIN_COMMISSION_RATE);

        return view('admin.revenue.report', compact('bookings', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Get date range based on period
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();

        return match ($period) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay()
            ],
            'yesterday' => [
                'start' => $now->copy()->subDay()->startOfDay(),
                'end' => $now->copy()->subDay()->endOfDay()
            ],
            'this_week' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek()
            ],
            'last_week' => [
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end' => $now->copy()->subWeek()->endOfWeek()
            ],
            'this_month' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ],
            'last_month' => [
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end' => $now->copy()->subMonth()->endOfMonth()
            ],
            'this_year' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear()
            ],
            'last_year' => [
                'start' => $now->copy()->subYear()->startOfYear(),
                'end' => $now->copy()->subYear()->endOfYear()
            ],
            default => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth()
            ]
        };
    }

    /**
     * Get monthly revenue for the last 12 months
     */
    private function getMonthlyRevenue()
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(11)->startOfMonth(); // Last 12 months

        // Generate all months for the last 12 months
        $months = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $key = $date->format('Y-m');
            $months[$key] = [
                'year' => $date->year,
                'month' => $date->month,
                'month_name' => $date->format('M Y'),
                'total_revenue' => 0,
                'booking_count' => 0,
                'admin_commission' => 0
            ];
        }

        // Get ALL booking data (no status filter) to see what we have
        $bookingData = Booking::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Merge actual data with the complete month structure
        foreach ($bookingData as $item) {
            $key = sprintf('%04d-%02d', $item->year, $item->month);
            if (isset($months[$key])) {
                $months[$key]['total_revenue'] = $item->total_revenue;
                $months[$key]['booking_count'] = $item->booking_count;
                $months[$key]['admin_commission'] = $item->total_revenue * self::ADMIN_COMMISSION_RATE;
            }
        }

        return collect(array_values($months))->map(function ($item) {
            return (object) [
                'year' => $item['year'],
                'month' => $item['month'],
                'month_name' => $item['month_name'],
                'total_revenue' => (float) $item['total_revenue'],
                'booking_count' => (int) $item['booking_count'],
                'admin_commission' => (float) $item['admin_commission']
            ];
        });
    }

    /**
     * Get top hotels by commission earned
     */
    private function getTopHotelsByCommission($dateRange)
    {
        return Booking::select('hotel_id')
            ->selectRaw('SUM(total_amount) as total_booking_value')
            ->selectRaw('COUNT(*) as booking_count')
            ->with('hotel')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('hotel_id')
            ->orderByRaw('SUM(total_amount) DESC')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'hotel_id' => $item->hotel_id,
                    'hotel' => $item->hotel,
                    'total_booking_value' => (float) $item->total_booking_value,
                    'booking_count' => (int) $item->booking_count,
                    'admin_commission' => (float) $item->total_booking_value * self::ADMIN_COMMISSION_RATE
                ];
            });
    }

    /**
     * Get hotel revenue breakdown
     */
    private function getHotelRevenueBreakdown($dateRange)
    {
        return Booking::select('hotel_id')
            ->selectRaw('SUM(total_amount) as total_booking_value')
            ->selectRaw('COUNT(*) as booking_count')
            ->selectRaw('AVG(total_amount) as avg_booking_value')
            ->with('hotel')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('hotel_id')
            ->orderByRaw('SUM(total_amount) DESC')
            ->get()
            ->map(function ($item) {
                $totalBookingValue = (float) $item->total_booking_value;
                return (object) [
                    'hotel_id' => $item->hotel_id,
                    'hotel' => $item->hotel,
                    'total_booking_value' => $totalBookingValue,
                    'booking_count' => (int) $item->booking_count,
                    'avg_booking_value' => (float) $item->avg_booking_value,
                    'admin_commission' => $totalBookingValue * self::ADMIN_COMMISSION_RATE,
                    'hotel_revenue' => $totalBookingValue * (1 - self::ADMIN_COMMISSION_RATE)
                ];
            });
    }

    /**
     * Get daily revenue for current period
     */
    private function getDailyRevenue($dateRange)
    {
        return Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'date' => $item->date,
                    'total_revenue' => (float) $item->total_revenue,
                    'booking_count' => (int) $item->booking_count,
                    'admin_commission' => (float) $item->total_revenue * self::ADMIN_COMMISSION_RATE
                ];
            });
    }

    /**
     * Get daily revenue for chart (last 7 days)
     */
    private function getDailyRevenueForChart()
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // Last 7 days including today

        // Get ALL booking data first to see what we have
        $bookingData = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'date' => $item->date,
                    'total_revenue' => (float) $item->total_revenue,
                    'booking_count' => (int) $item->booking_count,
                    'admin_commission' => (float) $item->total_revenue * self::ADMIN_COMMISSION_RATE
                ];
            });

        // If no booking data, generate empty structure for all 7 days
        if ($bookingData->isEmpty()) {
            $dates = [];
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $dates[] = (object) [
                    'date' => $date->format('Y-m-d'),
                    'total_revenue' => 0,
                    'booking_count' => 0,
                    'admin_commission' => 0
                ];
            }
            return collect($dates);
        }

        // If we have booking data, fill gaps for missing days
        $dates = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $bookingsByDate = $bookingData->keyBy('date');
        
        $result = collect($dates)->map(function ($date) use ($bookingsByDate) {
            if (isset($bookingsByDate[$date])) {
                return $bookingsByDate[$date];
            } else {
                return (object) [
                    'date' => $date,
                    'total_revenue' => 0,
                    'booking_count' => 0,
                    'admin_commission' => 0
                ];
            }
        });

        return $result;
    }
}
