<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelRevenueController extends Controller
{
    private const HOTEL_REVENUE_RATE = 0.90; // 90%
    private const ADMIN_COMMISSION_RATE = 0.10; // 10%

    /**
     * Display hotel revenue dashboard
     */
    public function index(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period);

        // Get ALL bookings for this hotel within date range (removed status filter)
        $bookings = Booking::with(['tourist', 'roomType'])
            ->where('hotel_id', $hotel->id)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();

        // Calculate revenue metrics
        $totalBookings = $bookings->count();
        $totalBookingValue = $bookings->sum('total_amount');
        $hotelRevenue = $totalBookingValue * self::HOTEL_REVENUE_RATE;
        $adminCommission = $totalBookingValue * self::ADMIN_COMMISSION_RATE;

        // Revenue by month for chart (last 12 months)
        $monthlyRevenue = $this->getMonthlyRevenue($hotel->id);

        // Revenue by room type
        $roomTypeRevenue = $this->getRoomTypeRevenue($hotel->id, $dateRange);

        // Daily revenue for last 7 days (for chart)
        $dailyRevenueChart = $this->getDailyRevenueForChart($hotel->id);
        
        // Daily revenue for selected period (for table)
        $dailyRevenue = $this->getDailyRevenue($hotel->id, $dateRange);

        // Top guests by spending
        $topGuests = $this->getTopGuests($hotel->id, $dateRange);

        // Revenue trends
        $revenueTrends = $this->getRevenueTrends($hotel->id);

        return view('hotel.revenue.index', compact(
            'totalBookings',
            'totalBookingValue',
            'hotelRevenue',
            'adminCommission',
            'monthlyRevenue',
            'roomTypeRevenue',
            'dailyRevenue',
            'dailyRevenueChart',
            'topGuests',
            'revenueTrends',
            'period'
        ));
    }

    /**
     * Get detailed revenue report
     */
    public function report(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $bookings = Booking::with(['tourist', 'roomType'])
            ->where('hotel_id', $hotel->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add revenue calculation to each booking
        $bookings->getCollection()->transform(function ($booking) {
            $booking->hotel_revenue = $booking->total_amount * self::HOTEL_REVENUE_RATE;
            $booking->admin_commission = $booking->total_amount * self::ADMIN_COMMISSION_RATE;
            return $booking;
        });

        $summary = [
            'total_bookings' => $bookings->total(),
            'total_booking_value' => Booking::where('hotel_id', $hotel->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereIn('booking_status', ['confirmed', 'completed'])
                ->sum('total_amount'),
            'hotel_revenue' => 0,
            'admin_commission' => 0
        ];

        $summary['hotel_revenue'] = $summary['total_booking_value'] * self::HOTEL_REVENUE_RATE;
        $summary['admin_commission'] = $summary['total_booking_value'] * self::ADMIN_COMMISSION_RATE;

        return view('hotel.revenue.report', compact('bookings', 'summary', 'startDate', 'endDate'));
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
    private function getMonthlyRevenue($hotelId)
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
                'hotel_revenue' => 0,
                'admin_commission' => 0
            ];
        }

        // Get actual booking data (removed status filter)
        $bookingData = Booking::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->where('hotel_id', $hotelId)
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Merge actual data with the complete month structure
        foreach ($bookingData as $item) {
            $key = sprintf('%04d-%02d', $item->year, $item->month);
            if (isset($months[$key])) {
                $months[$key]['total_revenue'] = (float) $item->total_revenue;
                $months[$key]['booking_count'] = $item->booking_count;
                $months[$key]['hotel_revenue'] = $item->total_revenue * self::HOTEL_REVENUE_RATE;
                $months[$key]['admin_commission'] = $item->total_revenue * self::ADMIN_COMMISSION_RATE;
            }
        }

        return collect(array_values($months))->map(function ($item) {
            return (object) $item;
        });
    }

    /**
     * Get revenue breakdown by room type
     */
    private function getRoomTypeRevenue($hotelId, $dateRange)
    {
        $data = Booking::select('room_type_id')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->selectRaw('COUNT(*) as booking_count')
            ->selectRaw('AVG(total_amount) as avg_booking_value')
            ->selectRaw('SUM(rooms_booked) as total_rooms_booked')
            ->with('roomType')
            ->where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('room_type_id')
            ->orderByRaw('SUM(total_amount) DESC')
            ->get();

        return $data->map(function ($item) {
            return (object) [
                'room_type_id' => $item->room_type_id,
                'roomType' => $item->roomType,
                'total_revenue' => (float) $item->total_revenue,
                'booking_count' => $item->booking_count,
                'avg_booking_value' => (float) $item->avg_booking_value,
                'total_rooms_booked' => $item->total_rooms_booked,
                'hotel_revenue' => $item->total_revenue * self::HOTEL_REVENUE_RATE,
                'admin_commission' => $item->total_revenue * self::ADMIN_COMMISSION_RATE
            ];
        });
    }

    /**
     * Get daily revenue for current period
     */
    private function getDailyRevenue($hotelId, $dateRange)
    {
        $data = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $data->map(function ($item) {
            return (object) [
                'date' => $item->date,
                'total_revenue' => (float) $item->total_revenue,
                'booking_count' => $item->booking_count,
                'hotel_revenue' => $item->total_revenue * self::HOTEL_REVENUE_RATE,
                'admin_commission' => $item->total_revenue * self::ADMIN_COMMISSION_RATE
            ];
        });
    }

    /**
     * Get daily revenue for chart (last 7 days)
     */
    private function getDailyRevenueForChart($hotelId)
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // Last 7 days including today

        // Get actual booking data
        $bookingData = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Generate all dates for the last 7 days
        $dates = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $bookingsByDate = $bookingData->keyBy('date');
        
        $result = collect($dates)->map(function ($date) use ($bookingsByDate) {
            if (isset($bookingsByDate[$date])) {
                $item = $bookingsByDate[$date];
                return (object) [
                    'date' => $item->date,
                    'total_revenue' => (float) $item->total_revenue,
                    'booking_count' => $item->booking_count,
                    'hotel_revenue' => $item->total_revenue * self::HOTEL_REVENUE_RATE,
                    'admin_commission' => $item->total_revenue * self::ADMIN_COMMISSION_RATE
                ];
            } else {
                return (object) [
                    'date' => $date,
                    'total_revenue' => 0,
                    'booking_count' => 0,
                    'hotel_revenue' => 0,
                    'admin_commission' => 0
                ];
            }
        });

        return $result;
    }

    /**
     * Get top guests by spending
     */
    private function getTopGuests($hotelId, $dateRange)
    {
        return Booking::select('tourist_id')
            ->selectRaw('SUM(total_amount) as total_spent')
            ->selectRaw('COUNT(*) as booking_count')
            ->selectRaw('AVG(total_amount) as avg_booking_value')
            ->with('tourist')
            ->where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('tourist_id')
            ->orderByRaw('SUM(total_amount) DESC')
            ->limit(10)
            ->get();
    }

    /**
     * Get revenue trends compared to previous period
     */
    private function getRevenueTrends($hotelId)
    {
        $currentMonth = Carbon::now();
        $previousMonth = $currentMonth->copy()->subMonth();

        $currentRevenue = Booking::where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$currentMonth->startOfMonth(), $currentMonth->endOfMonth()])
            ->sum('total_amount');

        $previousRevenue = Booking::where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$previousMonth->startOfMonth(), $previousMonth->endOfMonth()])
            ->sum('total_amount');

        $growthRate = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        return [
            'current_month_revenue' => $currentRevenue * self::HOTEL_REVENUE_RATE,
            'previous_month_revenue' => $previousRevenue * self::HOTEL_REVENUE_RATE,
            'growth_rate' => $growthRate,
            'growth_amount' => ($currentRevenue - $previousRevenue) * self::HOTEL_REVENUE_RATE
        ];
    }
}
