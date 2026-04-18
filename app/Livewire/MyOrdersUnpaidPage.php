<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Title('My Orders Unpaid - TegarJaya')]
class MyOrdersUnpaidPage extends Component
{
    use WithPagination;

    public $search = '';

    // Mode Pelunasan
    public $modePelunasan = false;
    public $tanggalBayar  = '';
    public $metodeBayar   = 'cash';
    public $rekeningBayar = 'KAS UTAMA';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function changeStatus($id)
    {
        if (!Auth::check() || auth()->user()->is_admin != 1) return;

        $data = Order::find($id);
        if (!$data) return;

        $nextStatus = match ($data->status) {
            'new'        => 'processing',
            'processing' => 'shipped',
            'shipped'    => 'delivered',
            'delivered'  => 'canceled',
            default      => 'new',
        };

        $data->update([
            'status'     => $nextStatus,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        OrderItem::where('order_id', $id)->update([
            'status'     => $nextStatus,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);
    }

    public function updatedMetodeBayar($value)
    {
        $this->rekeningBayar = $value === 'cash' ? 'KAS UTAMA' : 'BANK BCA';
    }    

    public function lunas($orderId)
    {
        if (!Auth::check() || auth()->user()->is_admin != 1) return;

        if (!$this->tanggalBayar) {
            $this->addError('pelunasan', 'Tanggal pembayaran wajib diisi.');
            return;
        }

        DB::transaction(function () use ($orderId) {
            $order = Order::whereKey($orderId)->lockForUpdate()->first();

            if (!$order || $order->is_paid) return;

            $totalPaid = Payment::where('paymentable_id', $order->id)
                ->where('paymentable_type', Order::class)
                ->where('mutation_type', 'Sales')
                ->sum('nominal_plus');

            $remaining = $order->grand_total - $totalPaid;

            if ($remaining <= 0) return;

            // Cegah double pelunasan
            $exists = Payment::where('paymentable_id', $order->id)
                ->where('paymentable_type', Order::class)
                ->where('mutation_type', 'Sales')
                ->where('nominal_plus', $remaining)
                ->exists();

            if ($exists) return;

            Payment::create([
                'date_payment'     => $this->tanggalBayar,
                'currency'         => 'idr',
                'payment_method'   => $this->metodeBayar,
                'rekening'         => $this->rekeningBayar,
                'nominal_plus'     => $remaining,
                'nominal'          => $remaining,
                'mutation_type'    => 'Sales',
                'debit'            => 'NR-DB-B-1100 CASH / BANK',
                'kredit'           => 'NR-DB-B-3000 Piutang Penjualan Barang',
                'created_by'       => Auth::id(),
                'updated_by'       => Auth::id(),
                'user_id'          => $order->user_id,
                'branch_id'        => Auth::user()->branch_id,
                'paymentable_id'   => $order->id,
                'paymentable_type' => Order::class,
            ]);

            $order->update([
                'is_paid'        => true,
                'total_payment'  => $totalPaid + $remaining,
                'total_cashback' => 0,
                'paid_at'        => now(),
                'status'         => 'delivered',
            ]);
        });
    }

    public function render()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        $isadmin = $user->is_admin;
        $today   = Carbon::now()->format('Y-m-d');

        // Default tanggal bayar
        if (!$this->tanggalBayar) {
            $this->tanggalBayar = Carbon::now()->format('Y-m-d\TH:i');
        }

        // Query Utama + Search
        $query = Order::where('branch_id', $user->branch_id)
            ->where('is_paid', 0)
            ->orderBy('date_order', 'desc');

        if ($isadmin != 1) {
            $query->where('user_id', $user->id);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('code_tr', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(120);

        // Statistik
        $unpaid_query = Order::where('branch_id', $user->branch_id)
            ->where('is_paid', 0)
            ->where('status', '!=', 'canceled');

        $my_orders_sum_unpaid       = $unpaid_query->sum('total_cashback');
        $my_orders_sum_unpaid_count = $unpaid_query->count();
        $my_orders_sum              = Order::where('user_id', $user->id)->sum('grand_total');

        // Payments
        $payment_query = Payment::where('mutation_type', 'Sales')
            ->where('branch_id', $user->branch_id)
            ->whereDate('date_payment', $today);

        $paymentcash = (clone $payment_query)->where('payment_method', 'cash')->sum('nominal_plus');
        $paymenttf   = (clone $payment_query)->where('payment_method', 'transfer')->sum('nominal_plus');

        $orderIds    = $orders->pluck('id');
        $paymentlast = Payment::where('mutation_type', 'Sales')
            ->where('paymentable_type', Order::class)
            ->whereIn('paymentable_id', $orderIds)
            ->latest('id')
            ->get();

        return view('livewire.my-orders-unpaid-page', [
            'orders'                    => $orders,
            'my_orders_sum'             => $my_orders_sum,
            'my_orders_sum_unpaid'      => $my_orders_sum_unpaid,
            'my_orders_sum_unpaid_count'=> $my_orders_sum_unpaid_count,
            'isadmin'                   => $isadmin,
            'today'                     => $today,
            'paymentlast'               => $paymentlast,
            'paymentcash'               => $paymentcash,
            'paymenttf'                 => $paymenttf,
        ]);
    }
}