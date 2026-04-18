<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Controllers\PrintController;
use App\Models\Branch;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

#[Title('My Orders Unpaid - TegarJaya')]
class MyOrdersUnpaidPage extends Component
{
   use WithPagination;

    public function changeStatus($id)
    {
        if (Auth::check()) {
            $data = Order::find($id);
            if (!$data) return;

            $statusnow = $data->status;
            $admin_id = Auth::id();
            $now = now();

            if (auth()->user()->is_admin == 1) {
                $nextStatus = match ($statusnow) {
                    'new'        => 'processing',
                    'processing' => 'shipped',
                    'shipped'    => 'delivered',
                    'delivered'  => 'canceled',
                    default      => 'new',
                };
                
                $data->update([
                    'status' => $nextStatus,
                    'updated_by' => $admin_id,
                    'updated_at' => $now,
                ]);

                OrderItem::where('order_id', $id)->update([
                    'status' => $nextStatus,
                    'updated_by' => $admin_id,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function render()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        $isadmin = $user->is_admin;
        $today = Carbon::now()->format('Y-m-d');

        // Query Utama
        if ($isadmin == 1) {
            $orders = Order::where('branch_id', $user->branch_id)
                ->where('is_paid', 0)
                ->orderBy('date_order', 'desc')
                ->paginate(120);
        } else {
            $orders = Order::where('user_id', $user->id)
                ->latest()
                ->paginate(120);
        }

        // Statistik - Perhatikan sum() tidak menggunakan value()
        $my_orders_sum = Order::where('user_id', $user->id)->sum('grand_total');
        
        $unpaid_query = Order::where('branch_id', $user->branch_id)
            ->where('is_paid', 0)
            ->where('status', '!=', 'canceled');

        $my_orders_sum_unpaid = $unpaid_query->sum('total_cashback');
        $my_orders_sum_unpaid_count = $unpaid_query->count();

        // Payments
        $payment_query = Payment::where('mutation_type', 'Sales')
            ->where('branch_id', $user->branch_id)
            ->whereDate('date_payment', $today);

        $paymentcash = $payment_query->clone()->where('payment_method', 'cash')->sum('nominal_plus') ?? 0;
        $paymenttf = $payment_query->clone()->where('payment_method', 'transfer')->sum('nominal_plus') ?? 0;

        // PERBAIKAN: Gunakan whereColumn untuk membandingkan 2 nama kolom
        $my_orders_sum_cashback = Order::where('is_paid', 1)
            ->whereDate('paid_at', $today)
            ->whereColumn('total_payment', '>=', 'grand_total')
            ->sum('total_cashback') ?? 0;

        // Ambil ID order yang sedang ditampilkan
        $orderIds = $orders->pluck('id');

        // ✅ Filter hanya payment untuk order yang tampil di halaman ini
        $paymentlast = Payment::where('mutation_type', 'Sales')
            ->where('paymentable_type', Order::class)
            ->whereIn('paymentable_id', $orderIds)
            ->latest('id')
            ->get();

        return view('livewire.my-orders-unpaid-page', [
            'orders' => $orders,
            'my_orders_sum' => $my_orders_sum,
            'my_orders_sum_cashback' => $my_orders_sum_cashback,
            'my_orders_sum_unpaid' => $my_orders_sum_unpaid,
            'my_orders_sum_unpaid_count' => $my_orders_sum_unpaid_count,
            'isadmin' => $isadmin,
            'today' => $today,
            'paymentlast' => $paymentlast,
            'paymentcash' => $paymentcash,
            'paymenttf' => $paymenttf,
            'branch' => Branch::all(),
        ]);
    }    
}
