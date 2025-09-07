@extends('hotel.layouts.app')

@section('title', 'Hotel Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth('hotel')->user()->name ?? 'Hotel' }}</p>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <i class="fas fa-calendar"></i>
            <span>{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Rooms -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Rooms</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-bed text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Bookings Today -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Bookings Today</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Occupancy Rate</p>
                    <p class="text-2xl font-bold text-gray-800">0%</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">$0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
                <a href="#" class="text-green-600 hover:text-green-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>No recent bookings</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <button class="p-4 bg-gradient-to-r from-green-50 to-teal-50 border border-green-200 rounded-lg hover:from-green-100 hover:to-teal-100 transition-all duration-200 text-left">
                    <i class="fas fa-plus text-green-600 mb-2"></i>
                    <p class="text-sm font-medium text-gray-800">Add Room</p>
                </button>
                <button class="p-4 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-lg hover:from-blue-100 hover:to-cyan-100 transition-all duration-200 text-left">
                    <i class="fas fa-calendar-plus text-blue-600 mb-2"></i>
                    <p class="text-sm font-medium text-gray-800">New Booking</p>
                </button>
                <button class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg hover:from-purple-100 hover:to-pink-100 transition-all duration-200 text-left">
                    <i class="fas fa-chart-bar text-purple-600 mb-2"></i>
                    <p class="text-sm font-medium text-gray-800">View Reports</p>
                </button>
                <button class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg hover:from-yellow-100 hover:to-orange-100 transition-all duration-200 text-left">
                    <i class="fas fa-cog text-yellow-600 mb-2"></i>
                    <p class="text-sm font-medium text-gray-800">Settings</p>
                </button>
            </div>
        </div>
    </div>
@endsection
