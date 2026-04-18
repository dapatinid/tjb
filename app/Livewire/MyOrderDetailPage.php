<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;

#[Title('Order Detail - TegarJaya')]
class MyOrderDetailPage extends Component
{
    public $order_id;

    // Add Payment
    public $payment_date_paid;
    public $payment_method  = 'cash';
    public $rekening        = 'KAS KASIR';
    public $input_payment;

    // Edit Payment
    public $payment_date_paid_edit;
    public $payment_method_edit = 'cash';
    public $rekening_edit       = 'KAS KASIR';
    public $input_payment_edit;

    // Return
    public $qty_return;

    public function mount($id)
    {
        $order = Order::findOrFail($id);

        if (Auth::user()->is_admin == 1) {
            if ($order->branch_id != Auth::user()->branch_id) {
                return $this->redirect('/my-orders', navigate: true);
            }
        } else {
            if (Auth::id() != $order->user_id) {
                return $this->redirect('/my-orders', navigate: true);
            }
        }

        $this->order_id        = $id;
        $this->payment_date_paid      = now()->format('Y-m-d\TH:i');
        $this->payment_date_paid_edit = now()->format('Y-m-d\TH:i');
    }

    // ✅ Reset rekening saat metode add berubah
    public function updatedPaymentMethod($value)
    {
        $this->rekening = $value === 'cash' ? 'KAS KASIR' : 'BANK BCA';
    }

    // ✅ Reset rekening saat metode edit berubah
    public function updatedPaymentMethodEdit($value)
    {
        $this->rekening_edit = $value === 'cash' ? 'KAS KASIR' : 'BANK BCA';
    }

    public function addPayment($orderId)
    {
        $this->validate([
            'payment_date_paid' => 'required',
            'payment_method'    => 'required',
            'rekening'          => 'required',
            'input_payment'     => 'required|numeric|min:1',
        ]);

        $order    = Order::findOrFail($orderId);
        $nominal  = (float) str_replace(['.', ','], ['', '.'], $this->input_payment);
        $payment_method_rekening = str_contains($this->rekening, 'KAS') ? 'cash' : 'transfer';

        DB::transaction(function () use ($order, $nominal, $payment_method_rekening) {
            Payment::create([
                'date_payment'     => $this->payment_date_paid,
                'currency'         => 'idr',
                'payment_method'   => $payment_method_rekening,
                'rekening'         => $this->rekening,
                'nominal_plus'     => $nominal,
                'nominal'          => $nominal,
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

            $totalPaid = Payment::where('paymentable_id', $order->id)
                ->where('paymentable_type', Order::class)
                ->where('mutation_type', 'Sales')
                ->sum('nominal_plus');

            $cashback = $totalPaid - $order->grand_total;
            $isPaid   = $totalPaid >= $order->grand_total;

            $order->update([
                'total_payment'  => $totalPaid,
                'total_cashback' => $cashback,
                'is_paid'        => $isPaid,
                'paid_at'        => $isPaid ? now() : $order->paid_at,
            ]);
        });

        $this->reset(['input_payment']);
    }

    public function editPayment($paymentId)
    {
        $this->validate([
            'payment_date_paid_edit' => 'required',
            'payment_method_edit'    => 'required',
            'rekening_edit'          => 'required',
            'input_payment_edit'     => 'required|numeric|min:1',
        ]);

        $payment = Payment::findOrFail($paymentId);
        $nominal = (float) str_replace(['.', ','], ['', '.'], $this->input_payment_edit);
        $payment_method_rekening = str_contains($this->rekening_edit, 'KAS') ? 'cash' : 'transfer';

        DB::transaction(function () use ($payment, $nominal, $payment_method_rekening) {
            $payment->update([
                'date_payment'   => $this->payment_date_paid_edit,
                'payment_method' => $payment_method_rekening,
                'rekening'       => $this->rekening_edit,
                'nominal_plus'   => $nominal,
                'nominal'        => $nominal,
                'updated_by'     => Auth::id(),
            ]);

            // Recalculate order totals
            $order     = Order::findOrFail($payment->paymentable_id);
            $totalPaid = Payment::where('paymentable_id', $order->id)
                ->where('paymentable_type', Order::class)
                ->where('mutation_type', 'Sales')
                ->sum('nominal_plus');

            $cashback = $totalPaid - $order->grand_total;
            $isPaid   = $totalPaid >= $order->grand_total;

            $order->update([
                'total_payment'  => $totalPaid,
                'total_cashback' => $cashback,
                'is_paid'        => $isPaid,
                'paid_at'        => $isPaid ? now() : $order->paid_at,
            ]);
        });

        $this->reset(['input_payment_edit']);
    }

    public function editReturn($itemId, $productId, $orderId)
    {
        $this->validate([
            'qty_return' => 'required|integer|min:1',
        ]);

        $item    = OrderItem::findOrFail($itemId);
        $product = Product::findOrFail($productId);

        DB::transaction(function () use ($item, $product, $orderId) {
            // Buat order item retur (quantity negatif)
            OrderItem::create([
                'order_id'      => $orderId,
                'product_id'    => $product->id,
                'branch_id'     => Auth::user()->branch_id,
                'mutation_type' => 'Return',
                'quantity'      => $this->qty_return * -1,
                'p_quantity'    => 0,
                'unit_amount'   => $item->unit_amount,
                'total_amount'  => $item->unit_amount * $this->qty_return * -1,
                'status'        => $item->status,
                'date_order'    => now(),
                'created_by'    => Auth::id(),
                'updated_by'    => Auth::id(),
            ]);
        });

        $this->reset(['qty_return']);
    }

    public function render()
    {
        $order = Order::with('branch')->findOrFail($this->order_id);

        $order_items = OrderItem::with('product')
            ->where('order_id', $this->order_id)
            ->get();

        $address = Address::where('order_id', $this->order_id)->first();

        $paymentslast = Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $this->order_id)
            ->latest()
            ->first();

        $payments = Payment::where('paymentable_type', Order::class)
            ->where('paymentable_id', $this->order_id)
            ->where('mutation_type', 'Sales')
            ->latest()
            ->get();

        $villages  = Village::whereIn('code', [$address->village  ?? ''])->get();
        $districts = District::whereIn('code', [$address->district ?? ''])->get();
        $cities    = City::whereIn('code', [$address->city        ?? ''])->get();
        $states    = Province::whereIn('code', [$address->state   ?? ''])->get();

        $paymentUserIds = $payments->pluck('updated_by')->filter()->unique();
        $user = User::whereIn('id', $paymentUserIds->merge([$order->user_id]))->get();

        return view('livewire.my-order-detail-page', [
            'order'             => $order,
            'order_items'       => $order_items,
            'address'           => $address,
            'address_firstname' => $address?->first_name,
            'address_lastname'  => $address?->last_name,
            'paymentslast'      => $paymentslast,
            'payments'          => $payments,
            'user'              => $user,
            'states'            => $states,
            'cities'            => $cities,
            'districts'         => $districts,
            'villages'          => $villages,
        ]);
    }
}