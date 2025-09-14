<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected $model;

    public function __construct($model = null)
    {
        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * Get date range for reports
     */
    protected function getDateRange($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return compact('startDate', 'endDate');
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin()
    {
        return Auth::user()->user_type === 'admin';
    }

    /**
     * Check if user is producer
     */
    protected function isProducer()
    {
        return Auth::user()->user_type === 'produsen';
    }

    /**
     * Get user scope for producer
     */
    protected function getUserScope()
    {
        if ($this->isProducer()) {
            return Auth::id();
        }
        return null;
    }

    /**
     * Format currency
     */
    protected function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Calculate percentage change
     */
    protected function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Get months in range
     */
    protected function getMonthsInRange($startDate, $endDate)
    {
        $months = [];
        $current = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();

        while ($current <= $end) {
            $months[] = $current->format('Y-m');
            $current->addMonth();
        }

        return $months;
    }

    /**
     * Export data to array format
     */
    protected function exportToArray($data, $headers = [])
    {
        $result = [];

        if (!empty($headers)) {
            $result[] = $headers;
        }

        foreach ($data as $item) {
            $result[] = is_array($item) ? $item : $item->toArray();
        }

        return $result;
    }
}
