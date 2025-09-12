<?php

namespace App\Services;

use App\Repositories\BookingRepository;

class BookingStatsService
{
    protected BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Get comprehensive booking statistics for a tourist.
     */
    public function getTouristBookingStats(int $touristId): array
    {
        return $this->bookingRepository->getAllStatusCounts($touristId);
    }

    /**
     * Get formatted booking statistics with labels.
     */
    public function getFormattedBookingStats(int $touristId): array
    {
        $stats = $this->getTouristBookingStats($touristId);
        
        return [
            'total' => [
                'count' => $stats['total'],
                'label' => 'Total Bookings',
                'icon' => 'fas fa-calendar-alt',
                'color' => 'text-blue-600'
            ],
            'confirmed' => [
                'count' => $stats['confirmed'],
                'label' => 'Confirmed',
                'icon' => 'fas fa-check-circle',
                'color' => 'text-green-600'
            ],
            'pending' => [
                'count' => $stats['pending'],
                'label' => 'Pending',
                'icon' => 'fas fa-clock',
                'color' => 'text-yellow-600'
            ],
            'completed' => [
                'count' => $stats['completed'],
                'label' => 'Completed',
                'icon' => 'fas fa-check-double',
                'color' => 'text-blue-600'
            ],
            'cancelled' => [
                'count' => $stats['cancelled'],
                'label' => 'Cancelled',
                'icon' => 'fas fa-times-circle',
                'color' => 'text-red-600'
            ],
        ];
    }

    /**
     * Calculate booking completion rate.
     */
    public function getCompletionRate(int $touristId): float
    {
        $stats = $this->getTouristBookingStats($touristId);
        
        if ($stats['total'] === 0) {
            return 0.0;
        }
        
        return round(($stats['completed'] / $stats['total']) * 100, 2);
    }

    /**
     * Get booking summary for dashboard.
     */
    public function getDashboardSummary(int $touristId): array
    {
        $stats = $this->getTouristBookingStats($touristId);
        $completionRate = $this->getCompletionRate($touristId);
        
        return [
            'stats' => $stats,
            'completion_rate' => $completionRate,
            'active_bookings' => $stats['confirmed'] + $stats['pending'],
            'has_bookings' => $stats['total'] > 0
        ];
    }
}