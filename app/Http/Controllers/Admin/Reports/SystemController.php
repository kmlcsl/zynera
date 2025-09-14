<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Display system reports (Admin only)
     */
    public function index(Request $request)
    {
        try {
            $systemInfo = $this->getSystemInfo();
            $databaseStats = $this->getDatabaseStats();
            $cacheStats = $this->getCacheStats();
            $storageStats = $this->getStorageStats();

            return view('admin.reports.system.index', compact(
                'systemInfo',
                'databaseStats',
                'cacheStats',
                'storageStats'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading system report: ' . $e->getMessage());
        }
    }

    /**
     * Performance monitoring
     */
    public function performanceMonitoring(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        try {
            $performanceMetrics = $this->getPerformanceMetrics($startDate, $endDate);
            $queryPerformance = $this->getQueryPerformance();
            $resourceUsage = $this->getResourceUsage();

            return view('admin.reports.system.performance', compact(
                'performanceMetrics',
                'queryPerformance',
                'resourceUsage',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading performance monitoring: ' . $e->getMessage());
        }
    }

    /**
     * Error logs analysis
     */
    public function errorLogs(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $level = $request->get('level', 'all');

        try {
            $errorStats = $this->getErrorStats($startDate, $endDate, $level);
            $recentErrors = $this->getRecentErrors($level, 50);
            $errorTrends = $this->getErrorTrends($startDate, $endDate);

            return view('admin.reports.system.error-logs', compact(
                'errorStats',
                'recentErrors',
                'errorTrends',
                'startDate',
                'endDate',
                'level'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading error logs: ' . $e->getMessage());
        }
    }

    /**
     * Export system data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'overview');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->prepareExportData($type);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'system_report_' . $type);
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting system data: ' . $e->getMessage());
        }
    }

    /**
     * Get system information
     */
    private function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'operating_system' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'environment' => config('app.env')
        ];
    }

    /**
     * Get database statistics
     */
    private function getDatabaseStats()
    {
        try {
            $tables = [
                'users' => DB::table('users')->count(),
                'products' => DB::table('products')->count(),
                'orders' => DB::table('orders')->count(),
                'order_items' => DB::table('order_items')->count(),
                'categories' => DB::table('categories')->count()
            ];

            // Database size (MySQL specific)
            $databaseName = config('database.connections.mysql.database');
            $sizeQuery = "SELECT
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables
                WHERE table_schema = '{$databaseName}'";

            $databaseSize = DB::select($sizeQuery)[0]->size_mb ?? 0;

            return [
                'tables' => $tables,
                'total_records' => array_sum($tables),
                'database_size_mb' => $databaseSize,
                'database_name' => $databaseName
            ];
        } catch (\Exception $e) {
            return [
                'tables' => [],
                'total_records' => 0,
                'database_size_mb' => 0,
                'database_name' => 'Unknown',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get cache statistics
     */
    private function getCacheStats()
    {
        try {
            $cacheDriver = config('cache.default');

            $stats = [
                'driver' => $cacheDriver,
                'status' => 'Active'
            ];

            // Test cache functionality
            $testKey = 'system_report_test_' . time();
            Cache::put($testKey, 'test_value', 60);
            $testResult = Cache::get($testKey);
            Cache::forget($testKey);

            $stats['functional'] = $testResult === 'test_value' ? 'Yes' : 'No';

            return $stats;
        } catch (\Exception $e) {
            return [
                'driver' => 'Unknown',
                'status' => 'Error',
                'functional' => 'No',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get storage statistics
     */
    private function getStorageStats()
    {
        try {
            $storagePath = storage_path();
            $publicPath = public_path();

            $stats = [
                'storage_path' => $storagePath,
                'public_path' => $publicPath,
                'storage_writable' => is_writable($storagePath) ? 'Yes' : 'No',
                'public_writable' => is_writable($publicPath) ? 'Yes' : 'No'
            ];

            // Calculate directory sizes
            $stats['storage_size'] = $this->getDirectorySize($storagePath);
            $stats['public_size'] = $this->getDirectorySize($publicPath);

            // Disk space
            $freeBytes = disk_free_space($storagePath);
            $totalBytes = disk_total_space($storagePath);

            $stats['disk_free_gb'] = round($freeBytes / 1024 / 1024 / 1024, 2);
            $stats['disk_total_gb'] = round($totalBytes / 1024 / 1024 / 1024, 2);
            $stats['disk_used_percent'] = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);

            return $stats;
        } catch (\Exception $e) {
            return [
                'storage_path' => 'Unknown',
                'public_path' => 'Unknown',
                'storage_writable' => 'Unknown',
                'public_writable' => 'Unknown',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics($startDate, $endDate)
    {
        // This is a simplified version - in production you'd use proper monitoring tools
        try {
            $metrics = [
                'average_response_time' => rand(100, 500) . 'ms', // Simulated
                'peak_response_time' => rand(500, 1000) . 'ms', // Simulated
                'total_requests' => rand(5000, 50000), // Simulated
                'error_rate' => rand(0, 5) . '%', // Simulated
                'uptime' => '99.9%', // Simulated
                'memory_peak_usage' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB'
            ];

            return $metrics;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get query performance
     */
    private function getQueryPerformance()
    {
        try {
            // Enable query log temporarily
            DB::enableQueryLog();

            // Run some sample queries to analyze
            DB::table('orders')->count();
            DB::table('products')->count();
            DB::table('users')->count();

            $queries = DB::getQueryLog();
            DB::disableQueryLog();

            $totalTime = array_sum(array_column($queries, 'time'));
            $avgTime = count($queries) > 0 ? $totalTime / count($queries) : 0;

            return [
                'total_queries' => count($queries),
                'total_time' => round($totalTime, 2) . 'ms',
                'average_time' => round($avgTime, 2) . 'ms',
                'slowest_query' => $queries ? max(array_column($queries, 'time')) . 'ms' : '0ms'
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get resource usage
     */
    private function getResourceUsage()
    {
        return [
            'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
            'memory_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB',
            'memory_limit' => ini_get('memory_limit'),
            'cpu_usage' => 'N/A', // Would need system tools to get actual CPU usage
            'load_average' => function_exists('sys_getloadavg') ? implode(', ', sys_getloadavg()) : 'N/A'
        ];
    }

    /**
     * Get error statistics (simplified)
     */
    private function getErrorStats($startDate, $endDate, $level = 'all')
    {
        // This is simplified - in production you'd parse actual log files
        return [
            'total_errors' => rand(0, 100),
            'critical_errors' => rand(0, 5),
            'warning_errors' => rand(0, 20),
            'info_errors' => rand(0, 50),
            'most_common_error' => 'Database connection timeout',
            'error_rate_trend' => 'Decreasing'
        ];
    }

    /**
     * Get recent errors (simplified)
     */
    private function getRecentErrors($level = 'all', $limit = 50)
    {
        // This is simplified - in production you'd parse actual log files
        $errors = [];
        for ($i = 0; $i < min($limit, 10); $i++) {
            $errors[] = [
                'timestamp' => Carbon::now()->subMinutes(rand(1, 1440))->format('Y-m-d H:i:s'),
                'level' => ['error', 'warning', 'info'][rand(0, 2)],
                'message' => 'Sample error message #' . ($i + 1),
                'file' => 'app/Http/Controllers/SampleController.php',
                'line' => rand(10, 200)
            ];
        }
        return $errors;
    }

    /**
     * Get error trends
     */
    private function getErrorTrends($startDate, $endDate)
    {
        // Simplified - would analyze actual log data in production
        $trends = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start <= $end) {
            $trends[] = [
                'date' => $start->format('Y-m-d'),
                'error_count' => rand(0, 10)
            ];
            $start->addDay();
        }

        return $trends;
    }

    /**
     * Get directory size in MB
     */
    private function getDirectorySize($directory)
    {
        try {
            $size = 0;
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            return round($size / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Prepare export data based on type
     */
    private function prepareExportData($type)
    {
        switch ($type) {
            case 'performance':
                return $this->exportPerformanceData();
            case 'errors':
                return $this->exportErrorData();
            default:
                return $this->exportOverviewData();
        }
    }

    /**
     * Export overview data
     */
    private function exportOverviewData()
    {
        $systemInfo = $this->getSystemInfo();
        $databaseStats = $this->getDatabaseStats();
        $storageStats = $this->getStorageStats();

        $data = [
            ['System Information', ''],
            ['PHP Version', $systemInfo['php_version']],
            ['Laravel Version', $systemInfo['laravel_version']],
            ['Operating System', $systemInfo['operating_system']],
            ['Memory Limit', $systemInfo['memory_limit']],
            ['Environment', $systemInfo['environment']],
            [''],
            ['Database Statistics', ''],
            ['Total Records', $databaseStats['total_records']],
            ['Database Size (MB)', $databaseStats['database_size_mb']],
            [''],
            ['Storage Information', ''],
            ['Storage Writable', $storageStats['storage_writable']],
            ['Disk Free (GB)', $storageStats['disk_free_gb'] ?? 'Unknown'],
            ['Disk Usage (%)', $storageStats['disk_used_percent'] ?? 'Unknown']
        ];

        return $data;
    }

    /**
     * Export performance data
     */
    private function exportPerformanceData()
    {
        $performance = $this->getPerformanceMetrics(null, null);
        $resources = $this->getResourceUsage();

        return [
            ['Performance Metrics', ''],
            ['Average Response Time', $performance['average_response_time']],
            ['Peak Response Time', $performance['peak_response_time']],
            ['Total Requests', $performance['total_requests']],
            ['Error Rate', $performance['error_rate']],
            ['Uptime', $performance['uptime']],
            [''],
            ['Resource Usage', ''],
            ['Memory Usage', $resources['memory_usage']],
            ['Memory Peak', $resources['memory_peak']],
            ['Load Average', $resources['load_average']]
        ];
    }

    /**
     * Export error data
     */
    private function exportErrorData()
    {
        $errorStats = $this->getErrorStats(null, null);
        $recentErrors = $this->getRecentErrors('all', 20);

        $data = [
            ['Error Statistics', ''],
            ['Total Errors', $errorStats['total_errors']],
            ['Critical Errors', $errorStats['critical_errors']],
            ['Warning Errors', $errorStats['warning_errors']],
            ['Most Common Error', $errorStats['most_common_error']],
            [''],
            ['Recent Errors', ''],
            ['Timestamp', 'Level', 'Message', 'File', 'Line']
        ];

        foreach ($recentErrors as $error) {
            $data[] = [
                $error['timestamp'],
                $error['level'],
                $error['message'],
                $error['file'],
                $error['line']
            ];
        }

        return $data;
    }

    /**
     * Download CSV file
     */
    private function downloadCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
