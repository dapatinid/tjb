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
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Title('Order Detail - TegarJaya')]
class MyOrderDetailPage extends Component
{

    public $payment_date_paid;
    public $payment_method;
    public $rekening;
    public $input_payment;

    public $payment_date_paid_edit;
    public $payment_method_edit;
    public $rekening_edit;
    public $input_payment_edit;

    public $qty_return;

    public function mount($order)
    {
        $this->order = $order;
        $branchid =  Order::find($order)->branch_id;
        $UserId =   Order::find($order)->user_id;

        if (Auth::user()->is_admin == 1) {
            if ($branchid != Auth::user()->branch_id) {
                // return redirect('/my-orders');
                return $this->redirect('/my-orders', navigate: true);
            }
        } elseif (Auth::user()->is_admin == 0) {
            if (Auth::user()->id != $UserId) {
                // return redirect('/my-orders');
                return $this->redirect('/my-orders', navigate: true);
            }
        }
    }

    // public function editReturn($id, $productID, $orderID)
    // {
    //     $itemID = OrderItem::find($id);
    //     $itemQTYbefore = $itemID->quantity;
    //     $itemQTY = $this->qty_return;
    //     $product = Product::find($productID);

    //     $cekQTYbefore = OrderItem::where('order_id', $orderID)->where('product_id', $productID)->sum('quantity');
    //     // dd($cekQTYbefore);

    //     if ($cekQTYbefore >= $itemQTY) {
    //         $itemQTY = $this->qty_return * -1;
    //     } else {
    //         $itemQTY = 0;
    //     }

    //     if ($itemQTY >= 0) {
    //         // redirect('/my-orders/' . $orderID);
    //         return $this->redirect('/my-orders/' . $orderID, navigate: true);
    //     } else {
    //         $newReturn = new OrderItem();
    //         $newReturn->product_id = $product->id;
    //         $newReturn->unit_name = $product->unit_name;
    //         $newReturn->contain = $product->contain;
    //         $newReturn->quantity = $itemQTY;
    //         $newReturn->unit_amount = $product->price;
    //         $newReturn->total_amount = $itemQTY * $product->price;
    //         $newReturn->notes = 'return, from ' . $itemQTYbefore . ' to ' . $itemQTYbefore + $itemQTY;
    //         $newReturn->mutation_type = 'Sales';
    //         $newReturn->order_id  = $orderID;
    //         $newReturn->created_by = Auth::user()->id;
    //         $newReturn->updated_by = Auth::user()->id;
    //         $newReturn->branch_id  = Auth::user()->branch_id;
    //         $newReturn->save();

    //         // update OrderItem selesai, lalu update Order

    //         $totalOrdItem = OrderItem::where('order_id', $orderID)->sum('total_amount');
    //         $dataOrder = Order::find($orderID);

    //         $discount = $dataOrder->discount;
    //         $shipping_amount = $dataOrder->shipping_amount;
    //         $grand_total = $totalOrdItem - $discount + $shipping_amount;

    //         $total_payment = $dataOrder->total_payment;
    //         $total_cashback = $total_payment - $grand_total;

    //         if ($total_cashback >= 0) {
    //             $is_paid = 1;
    //             $paid_at = $dataOrder->paid_at;
    //         } else {
    //             $is_paid = 0;
    //             $paid_at = null;
    //         }

    //         $updateOrder = [
    //             'discount' => $discount,
    //             'shipping_amount' => $shipping_amount,
    //             'grand_total' => $grand_total,
    //             'total_payment' => $total_payment,
    //             'total_cashback' => $total_cashback,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'updated_by' => Auth::user()->id,
    //             'is_paid' => $is_paid,
    //             'paid_at' => $paid_at,
    //         ];
    //         $dataOrder->update($updateOrder);

    //         // redirect('/my-orders/' . $orderID);
    //         return $this->redirect('/my-orders/' . $orderID, navigate: true);
    //     }
    // }

    // public function addPayment($id)
    // {
    //     $data = Order::find($id);
    //     $grand_total = $data->grand_total;
    //     $totalpaid = Payment::where('paymentable_type', Order::class)->where('mutation_type', "Sales")->where('paymentable_id', $id)->sum('nominal_plus');
    //     $this->input_payment = Str::replace('.', '', $this->input_payment);

    //     if (str_contains($this->rekening, 'BANK')) {
    //         $this->payment_method = 'transfer';
    //     } else {
    //         $this->payment_method = 'cash';
    //     }

    //     if ($this->payment_method == null) {
    //         $payment_method = 'cash';
    //     } else {
    //         $payment_method = $this->payment_method;
    //     }

    //     if ($this->input_payment == null) {
    //         redirect('/my-orders/' . $id);
    //     } else {
    //         $input_payment = $this->input_payment;


    //         if ($payment_method === 'transfer' && (($input_payment + $totalpaid) >= $grand_total)) {
    //             $payment_method = 'transfer';
    //             $input_payment = $grand_total - $totalpaid;
    //             $input_mins = 0;
    //             $input_final = $input_payment;
    //         } else {
    //             if (($input_payment + $totalpaid) >= $grand_total) {
    //                 $payment_method = $payment_method;
    //                 $input_payment = $input_payment;
    //                 $input_mins = ($input_payment + $totalpaid) - $grand_total;
    //                 $input_final = $input_payment - $input_mins;
    //             } else {
    //                 $payment_method = $payment_method;
    //                 $input_payment = $this->input_payment;
    //                 $input_mins = 0;
    //                 $input_final = $input_payment;
    //             }
    //         }

    //         if ($this->payment_date_paid == null) {
    //             $payment_date_paid = now();
    //         } else {
    //             $payment_date_paid = $this->payment_date_paid;
    //         }

    //         $payment = new Payment();
    //         $payment->date_payment = $payment_date_paid;
    //         $payment->currency = 'idr';
    //         $payment->debit = 'NR-DB-B-1100 CASH / BANK';
    //         $payment->kredit = 'NR-DB-B-3000 Piutang Penjualan Barang';
    //         $payment->rekening = $this->rekening;
    //         $payment->payment_method = $payment_method;
    //         $payment->user_id = $data->user_id;
    //         $payment->nominal_plus = $input_payment;
    //         $payment->nominal_mins = $input_mins;
    //         $payment->nominal = $input_final;
    //         $payment->mutation_type = 'Sales';
    //         $payment->created_by = Auth::user()->id;
    //         $payment->updated_by = Auth::user()->id;
    //         $payment->branch_id = $data->branch_id;
    //         $payment->paymentable_id = $id;
    //         $payment->paymentable_type = 'App\Models\Order';
    //         $payment->save();

    //         $total_payment_before = $data->total_payment;
    //         $total_payment = $total_payment_before + $input_payment;
    //         if ($grand_total - $total_payment <= 0) {
    //             $paidunpaid = 1;
    //             // $paidat = date('Y-m-d H:i:s');
    //             Payment::where('paymentable_type', Order::class)
    //                 ->where('paymentable_id', $id)
    //                 ->where('mutation_type', "Piutang Penjualan")
    //                 ->update([
    //                     // 'date_payment' => $lastEdit,
    //                     'date_payment' => $this->payment_date_paid_edit,
    //                     'nominal_plus' => $grand_total,
    //                     'nominal_mins' => 0,
    //                     'nominal' => $grand_total,
    //                 ]);
    //         } else {
    //             $paidunpaid = 0;
    //             // $paidat = null;
    //             Payment::where('paymentable_type', Order::class)
    //                 ->where('paymentable_id', $id)
    //                 ->where('mutation_type', "Piutang Penjualan")
    //                 ->update([
    //                     // 'date_payment' => $lastEdit,
    //                     'date_payment' => $this->payment_date_paid_edit,
    //                     'nominal_plus' => $grand_total,
    //                     'nominal_mins' => 0,
    //                     'nominal' => $grand_total,
    //                 ]);
    //         }
    //         $cashback = $total_payment - $grand_total;
    //         $update = [
    //             'is_paid' => $paidunpaid,
    //             'paid_at' => $this->payment_date_paid,
    //             'total_payment' => $total_payment,
    //             'total_cashback' => $cashback,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //         ];
    //         $data->update($update);
    //         $this->input_payment = '';
    //         // redirect('/my-orders/' . $id);
    //         // return $this->redirect('/my-orders/' . $id, navigate: true);
    //         return $this->redirect('/my-orders-unpaid/', navigate: true);
    //     }
    // }

    // public function editPayment($idpay)
    // {
    //     $datapayment = Payment::find($idpay);
    //     $orderID = $datapayment->paymentable_id;
    //     $totalpaid = Payment::where('paymentable_type', Order::class)->where('mutation_type', "Sales")->where('paymentable_id', $orderID)->where('id', '!=', $idpay)->sum('nominal_plus');
    //     $data = Order::find($orderID);
    //     $grand_total = $data->grand_total;
    //     $this->input_payment_edit = Str::replace('.', '', $this->input_payment_edit);

    //     if (str_contains($this->rekening_edit, 'BANK')) {
    //         $this->payment_method_edit = 'transfer';
    //     } else {
    //         $this->payment_method_edit = 'cash';
    //     }

    //     if ($this->payment_method_edit == null) {
    //         $payment_method_edit = 'cash';
    //     } else {
    //         $payment_method_edit = $this->payment_method_edit;
    //     }

    //     if ($this->input_payment_edit == null) {
    //         redirect('/my-orders/' . $orderID);
    //     } else {
    //         $input_payment_edit = $this->input_payment_edit;
    //         if ($payment_method_edit === 'transfer') {
    //             if (($this->input_payment_edit + $totalpaid) >= $grand_total) {
    //                 $payment_method_edit = 'transfer';
    //                 $input_payment_edit = $grand_total - $totalpaid;
    //                 $input_mins = 0;
    //                 $input_final = $input_payment_edit;
    //             } else {
    //                 $payment_method_edit = 'transfer';
    //                 $input_payment_edit = $this->input_payment_edit;
    //                 $input_mins = 0;
    //                 $input_final = $input_payment_edit;
    //             }
    //         } elseif ($payment_method_edit === 'cash') {
    //             if (($this->input_payment_edit + $totalpaid) >= $grand_total) {
    //                 $payment_method_edit = $payment_method_edit;
    //                 $input_payment_edit = $this->input_payment_edit;
    //                 $input_mins = ($input_payment_edit + $totalpaid) - $grand_total;
    //                 $input_final = $input_payment_edit - $input_mins;
    //             } else {
    //                 $payment_method_edit = $payment_method_edit;
    //                 $input_payment_edit = $this->input_payment_edit;
    //                 $input_mins = 0;
    //                 $input_final = $input_payment_edit;
    //             }
    //         }

    //         if ($this->payment_date_paid_edit == null) {
    //             $payment_date_paid_edit = now();
    //         } else {
    //             $payment_date_paid_edit = $this->payment_date_paid_edit;
    //         }

    //         $input_payment_edit = $input_payment_edit;

    //         $updatepayment = [
    //             'date_payment' => $payment_date_paid_edit,
    //             'payment_method' => $payment_method_edit,
    //             'rekening' => $this->rekening_edit,
    //             'user_id' => $data->user_id,
    //             'nominal_plus' => $input_payment_edit,
    //             'nominal_mins' => $input_mins,
    //             'nominal' => $input_final,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'updated_by' => Auth::user()->id,
    //         ];

    //         $datapayment->update($updatepayment);

    //         $total_payment = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $orderID)->where('mutation_type', "Sales")->sum('nominal_plus');
    //         if ($grand_total - $total_payment <= 0) {
    //             $paidunpaid = 1;
    //             // $paidat = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $orderID)->orderBy('date_payment', 'desc')->latest()->get()->value('date_payment');
    //             Payment::where('paymentable_type', Order::class)
    //                 ->where('paymentable_id', $orderID)
    //                 ->where('mutation_type', "Piutang Penjualan")
    //                 ->update([
    //                     // 'date_payment' => $lastEdit,
    //                     'date_payment' => $this->payment_date_paid_edit,
    //                     'nominal_plus' => $grand_total,
    //                     'nominal_mins' => 0,
    //                     'nominal' => $grand_total,
    //                 ]);
    //         } else {
    //             $paidunpaid = 0;
    //             // $paidat = null;
    //             Payment::where('paymentable_type', Order::class)
    //                 ->where('paymentable_id', $orderID)
    //                 ->where('mutation_type', "Piutang Penjualan")
    //                 ->update([
    //                     // 'date_payment' => $lastEdit,
    //                     'date_payment' => $this->payment_date_paid_edit,
    //                     'nominal_plus' => $grand_total,
    //                     'nominal_mins' => 0,
    //                     'nominal' => $grand_total,
    //                 ]);
    //         }
    //         $cashback = $total_payment - $grand_total;
    //         $update = [
    //             'is_paid' => $paidunpaid,
    //             'paid_at' => $this->payment_date_paid_edit,
    //             'total_payment' => $total_payment,
    //             'total_cashback' => $cashback,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //         ];
    //         $data->update($update);
    //         $this->input_payment_edit = '';
    //         // redirect('/my-orders/' . $orderID);
    //         return $this->redirect('/my-orders/' . $orderID, navigate: true);
    //     }
    // }

    public function render()
    {
        $order_items = OrderItem::with('product')->where('order_id', $this->order)->get();
        $paymentslast = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $this->order)->latest()->first();
        $address = Address::where('order_id', $this->order)->first();
        $address_firstname = Address::where('order_id', $this->order)->value('first_name');
        $address_lastname = Address::where('order_id', $this->order)->value('last_name');
        $order = Order::where('id', $this->order)->firstOrFail();
        $user = User::all();
        $states = Province::all();
        $cities = City::all();
        $districts = District::all();
        $villages = Village::all();
        $payments = Payment::all();


        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'paymentslast' => $paymentslast,
            'payments' => $payments,
            'address' => $address,
            'address_firstname' => $address_firstname,
            'address_lastname' => $address_lastname,
            'order' => $order,
            'user' => $user,
            'states' => $states,
            'cities' => $cities,
            'districts' => $districts,
            'villages' => $villages,
        ]);
    }
}
