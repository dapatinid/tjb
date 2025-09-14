<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Branch;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

#[Title('My Orders - TegarJaya')]
class MyOrdersPage extends Component
{
    use WithPagination;
    // protected $paginationTheme = 'bootstrap';

    public $id;

    public function changeStatus($id)
    {
        if (Auth::check()) {
            $data = Order::where('id', $id);
            $statusnow = $data->value('status');
            if (auth()->user()->is_admin == 1) {
                if ($statusnow == 'new') {
                    $update = [
                        'status' => 'processing',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'processing', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                if ($statusnow == 'processing') {
                    $update = [
                        'status' => 'shipped',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'shipped', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                if ($statusnow == 'shipped') {
                    $update = [
                        'status' => 'delivered',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'delivered', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                if ($statusnow == 'delivered') {
                    $update = [
                        'status' => 'canceled',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'canceled', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                if ($statusnow == 'canceled') {
                    $update = [
                        'status' => 'new',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'new', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                $data->update($update);
            } else {
                if ($statusnow == 'canceled') {
                    $update = [
                        'status' => 'canceled',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'canceled', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                } else {
                    $update = [
                        'status' => 'delivered',
                        'updated_by' => auth()->user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    OrderItem::where('order_id', $id)->update(['status' => 'delivered', 'updated_by' => auth()->user()->id, 'updated_at' => date('Y-m-d H:i:s'),]);
                }
                $data->update($update);
            }
        }
    }

    public function render()
    {
        $isadmin = auth()->user()->is_admin;
        if ($isadmin == 1) {
            $my_orders = Order::where('branch_id', auth()->user()->branch_id)->where('is_paid', 1)->orderBy('date_order', 'desc')->paginate(25);
        }
        if ($isadmin == 0) {
            $my_orders = Order::where('user_id', auth()->id())->latest()->paginate(25);
        }
        $orders_all = Order::all();
        $my_orders_all = Order::where('user_id', auth()->id());
        $my_orders_sum = $my_orders_all->sum(value('grand_total'));
        $today = Carbon::now()->format('Y-m-d');
        $my_orders_sum_unpaid = Order::where('branch_id', auth()->user()->branch_id)->where('is_paid', 0)->where('status', '!=', 'canceled')->sum(value('total_cashback'));
        $my_orders_sum_unpaid_count = Order::where('branch_id', auth()->user()->branch_id)->where('is_paid', 0)->where('status', '!=', 'canceled')->count();
        $paymentlast = Payment::where('mutation_type', 'Sales')->where('paymentable_type', Order::class)->orderBy('date_payment', 'desc')->get();
        $paymentcash = Payment::where('mutation_type', 'Sales')->where('branch_id', auth()->user()->branch_id)->where('date_payment', 'like', "%$today%")->where('payment_method', '=', 'cash')->sum(value('nominal_plus'));
        $paymenttf = Payment::where('mutation_type', 'Sales')->where('branch_id', auth()->user()->branch_id)->where('date_payment', 'like', "%$today%")->where('payment_method', '=', 'transfer')->sum(value('nominal_plus'));
        $my_orders_sum_cashback = Order::where('is_paid', 1)->where('paid_at', 'like', "%$today%")->where('total_payment', '>=', 'grand_total')->sum(value('total_cashback'));

        $payments = Payment::where('branch_id', auth()->user()->branch_id)->where('paymentable_type', Order::class)->where('mutation_type', 'Sales')
            ->where('date_payment', 'like', "%$today%")
            ->orderBy('id', 'desc')->get();

        $products = Product::all();
        $orderItems = OrderItem::whereNull('deleted_at');
        $itemsSold = OrderItem::where('branch_id', auth()->user()->branch_id)
            ->whereNotNull('order_id')
            ->whereNull('deleted_at')
            ->where('updated_at', 'like', "%$today%")
            ->orderBy('product_id', 'asc')->get();
        $itemsSoldGroup = OrderItem::where('branch_id', auth()->user()->branch_id)
            ->whereNotNull('order_id')
            ->whereNull('deleted_at')
            ->where('updated_at', 'like', "%$today%")
            ->orderBy('product_id', 'asc')->get()
            ->groupBy('product_id');

        $branch = Branch::all();

        return view('livewire.my-orders-page', [
            'payments' => $payments,
            'orders' => $my_orders,
            'orders_all' => $orders_all,
            'my_orders_sum' => $my_orders_sum,
            'my_orders_sum_cashback' => $my_orders_sum_cashback,
            'my_orders_sum_unpaid' => $my_orders_sum_unpaid,
            'my_orders_sum_unpaid_count' => $my_orders_sum_unpaid_count,
            'isadmin' => $isadmin,
            'today' => $today,
            'paymentlast' => $paymentlast,
            'paymentcash' => $paymentcash,
            'paymenttf' => $paymenttf,
            'products' => $products,
            'orderItems' => $orderItems,
            'itemsSold' => $itemsSold,
            'itemsSoldGroup' => $itemsSoldGroup,
            'branch' => $branch,
        ]);
    }
}
