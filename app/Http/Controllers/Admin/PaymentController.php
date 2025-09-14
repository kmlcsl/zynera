<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of payments based on user type
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $user->user_type;

        // Build query based on user type
        $query = Payment::with(['order.user', 'order.orderItems.product.user']);

        // Filter berdasarkan user type
        if ($userType === 'produsen') {
            // Produsen hanya bisa melihat pembayaran dari produk mereka
            $query->whereHas('order.orderItems.product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // FIX: Gunakan input() untuk mengakses field 'method'
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_range')) {
            $dateRange = $request->input('date_range');
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'quarter':
                    $query->where('created_at', '>=', now()->subMonths(3));
                    break;
            }
        }

        // Handle export
        if ($request->has('export')) {
            return $this->exportPayments($query, $request->input('export'));
        }

        // Get payments with pagination
        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $stats = $this->calculateStats($userType, $user);

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        // Typically payments are created automatically through orders
        // This might be used for manual payment entries by admin
        $orders = Order::where('status', '!=', 'cancelled')
            ->whereDoesntHave('payment')
            ->with('user')
            ->get();

        return view('admin.payments.create', compact('orders'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:cod,transfer,qris',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $payment = Payment::create($request->all());

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        // Check access based on user type
        $user = Auth::user();
        if ($user->user_type === 'produsen') {
            $hasAccess = $payment->order->orderItems()
                ->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->exists();

            if (!$hasAccess) {
                abort(403, 'Unauthorized access to payment data.');
            }
        }

        $payment->load(['order.user', 'order.orderItems.product', 'order.delivery']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment)
    {
        // Check access based on user type
        $user = Auth::user();
        if ($user->user_type === 'produsen') {
            $hasAccess = $payment->order->orderItems()
                ->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->exists();

            if (!$hasAccess) {
                abort(403, 'Unauthorized access to payment data.');
            }
        }

        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'method' => 'required|in:cod,transfer,qris',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified payment
     */
    public function destroy(Payment $payment)
    {
        // Only admin can delete payments
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Confirm payment (AJAX) - Admin only
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        if (Auth::user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment status is not pending.']);
        }

        try {
            DB::beginTransaction();

            // Update payment status
            $payment->update([
                'status' => 'paid',
                'paid_at' => Carbon::now(),
                'reference_number' => $request->input('reference_number') ?? 'ADMIN_CONFIRMED_' . time()
            ]);

            // Update order status
            $payment->order->update(['status' => 'paid']);

            // Clear cart for non-COD payments
            if ($payment->method !== 'cod') {
                Cart::where('user_id', $payment->order->user_id)->delete();
            }

            // Auto-create delivery record untuk tracking
            if (!$payment->order->delivery) {
                Delivery::create([
                    'order_id' => $payment->order->id,
                    'status' => 'assigned',
                    'assigned_at' => now(),
                    'notes' => 'Menunggu penugasan kurir oleh produsen'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi.',
                'payment' => $payment->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reject payment (AJAX) - Admin only
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        if (Auth::user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment status is not pending.']);
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'failed',
                'reference_number' => 'REJECTED: ' . $request->input('reason')
            ]);

            // Update order status
            $payment->order->update(['status' => 'failed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk confirm payments (AJAX) - Admin only
     */
    public function bulkConfirmPayments(Request $request)
    {
        if (Auth::user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        try {
            DB::beginTransaction();

            $payments = Payment::whereIn('id', $request->input('payment_ids'))
                ->where('status', 'pending')
                ->get();

            $confirmed = 0;
            foreach ($payments as $payment) {
                // Update payment status
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => Carbon::now(),
                    'reference_number' => 'BULK_CONFIRMED_' . time()
                ]);

                // Update order status
                $payment->order->update(['status' => 'paid']);

                // Clear cart for non-COD payments
                if ($payment->method !== 'cod') {
                    Cart::where('user_id', $payment->order->user_id)->delete();
                }

                $confirmed++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$confirmed} pembayaran berhasil dikonfirmasi."
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk reject payments (AJAX) - Admin only
     */
    public function bulkRejectPayments(Request $request)
    {
        if (Auth::user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $payments = Payment::whereIn('id', $request->input('payment_ids'))
                ->where('status', 'pending')
                ->get();

            $rejected = 0;
            foreach ($payments as $payment) {
                $payment->update([
                    'status' => 'failed',
                    'reference_number' => 'BULK_REJECTED: ' . $request->input('reason')
                ]);

                $payment->order->update(['status' => 'failed']);
                $rejected++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$rejected} pembayaran berhasil ditolak."
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export selected payments
     */
    public function exportSelected(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        $payments = Payment::whereIn('id', $request->input('payment_ids'))
            ->with(['order.user', 'order.orderItems.product'])
            ->get();

        return $this->generateExport($payments, 'selected_payments');
    }

    /**
     * Generate payment receipt
     */
    public function receipt(Payment $payment)
    {
        // Check access based on user type
        $user = Auth::user();
        if ($user->user_type === 'produsen') {
            $hasAccess = $payment->order->orderItems()
                ->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->exists();

            if (!$hasAccess) {
                abort(403, 'Unauthorized access to payment data.');
            }
        }

        // Only allow receipt for paid payments
        if ($payment->status !== 'paid') {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Kwitansi hanya tersedia untuk pembayaran yang sudah lunas.');
        }

        // Load necessary relationships
        $payment->load(['order.user', 'order.orderItems.product.user']);

        return view('admin.payments.receipt', compact('payment'));
    }

    /**
     * Calculate statistics based on user type
     */
    private function calculateStats($userType, $user)
    {
        $query = Payment::query();

        if ($userType === 'produsen') {
            $query->whereHas('order.orderItems.product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return [
            'total' => $query->count(),
            'paid' => $query->where('status', 'paid')->count(),
            'paid_amount' => $query->where('status', 'paid')->sum('amount'),
            'pending' => $query->where('status', 'pending')->count(),
            'pending_amount' => $query->where('status', 'pending')->sum('amount'),
            'failed' => $query->where('status', 'failed')->count(),
            'failed_amount' => $query->where('status', 'failed')->sum('amount'),
            'refunded' => $query->where('status', 'refunded')->count(),
            'refunded_amount' => $query->where('status', 'refunded')->sum('amount'),
        ];
    }

    /**
     * Export payments to Excel/PDF
     */
    private function exportPayments($query, $format = 'excel')
    {
        $payments = $query->with(['order.user', 'order.orderItems.product'])->get();

        return $this->generateExport($payments, 'payments_export', $format);
    }

    /**
     * Generate export file
     */
    private function generateExport($payments, $filename, $format = 'excel')
    {
        if ($format === 'excel') {
            return $this->exportToExcel($payments, $filename);
        } else {
            return $this->exportToPdf($payments, $filename);
        }
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($payments, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
        ];

        $data = $payments->map(function ($payment) {
            return [
                'ID' => $payment->id,
                'Order Number' => $payment->order->order_number ?? 'N/A',
                'Customer' => $payment->order->user->name ?? 'N/A',
                'Email' => $payment->order->user->email ?? 'N/A',
                'Method' => $payment->method_label,
                'Amount' => $payment->amount,
                'Status' => $payment->status_label,
                'Reference Number' => $payment->reference_number ?? 'N/A',
                'Created At' => $payment->created_at->format('Y-m-d H:i:s'),
                'Paid At' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'N/A',
            ];
        });

        // Simple CSV export (you can enhance this with a proper Excel library)
        $csv = "ID,Order Number,Customer,Email,Method,Amount,Status,Reference Number,Created At,Paid At\n";
        foreach ($data as $row) {
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }

        return response($csv, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportToPdf($payments, $filename)
    {
        // This would require a PDF library like DomPDF or similar
        // For now, return a simple HTML view that can be printed as PDF
        return view('admin.payments.export-pdf', compact('payments', 'filename'));
    }
}
