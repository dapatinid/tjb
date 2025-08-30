<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Porder extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'q',
        'code_tr',
        'user_id',
        'sales_type',
        'is_paid',
        'status',
        'shipping_amount',
        'shipping_method',
        'discount',
        'grand_total',
        'total_payment',
        'total_cashback',
        'notes',
        'total_weight',
        'date_order',
        'paid_at',
        'created_by',
        'updated_by',
        'updated_at',
        'branch_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->update(['updated_by' => auth()->user()->id,]);
        });
        static::deleted(function ($model) {
            if ($model->isForceDeleting()) {
                $model->items()->withTrashed()->forceDelete();
                $model->payments()->withTrashed()->forceDelete();
            } else {
                $model->items()->delete();
                $model->payments()->delete();
            }
        });
        static::restored(function ($model) {
            $model->items()->withTrashed()->restore();
            $model->payments()->withTrashed()->restore();
        });
        static::updating(function ($model) {
            if ($model->isDirty('date_order')) {
                $hari = Carbon::parse($model->date_order)->format('Y-m-d');
                $antri = Order::where('branch_id', auth()->user()->branch_id)->where('date_order', 'like', "%$hari%")->count() + 1;
                Order::where('id', $model->id)->update(['q' => $antri,]);
            }
            // hitung Hutang Pembelian untuk jurnal
            // if ($model->isDirty('grand_total')) {
            Payment::where('paymentable_type', Porder::class)
                ->where('paymentable_id', $model->id)
                ->where('mutation_type', "Hutang Pembelian")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $model->date_order,
                    'nominal_plus' => 0,
                    'nominal_mins' => $model->grand_total,
                    'nominal' => $model->grand_total,
                ]);
            // }
        });
        static::updated(function ($model) {
            // Update HPP tiap produk ketika melakukan Pembelian
            // $record = $this->record;
            $dataBelanja = OrderItem::where('porder_id', $model->id)->get();
            $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
            foreach ($dataBelanja as $item) {
                $boughtqty = $orderitems->where('product_id', $item->product_id)->sum('p_quantity');
                $soldqty = $orderitems->where('product_id', $item->product_id)->sum('quantity');
                $stokBefore_validate = $boughtqty - $soldqty - $item->p_quantity;
                if ($stokBefore_validate <= 0) {
                    $stokBefore = 0;
                } else {
                    $stokBefore = $stokBefore_validate;
                }
                $stokAdd = $item->p_quantity;
                $stokAfter = $stokBefore + $stokAdd;

                $itemUpdate = Product::where('id', $item->product_id);
                $hppLama = $itemUpdate->value('cogs');
                $hppBaru = ($item->p_unit_amount == 0) ? $hppLama : $item->p_unit_amount;
                $hppAvg = (($hppLama * $stokBefore) + ($hppBaru * $stokAdd)) / $stokAfter;
                $update = ['cogs' => $hppAvg];
                $itemUpdate->update($update);
            }


            $orderTarget = Porder::where('id', $model->id);
            $user_id = Porder::where('id', $model->id)->value('user_id');
            // $lastEdit = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $model->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('date_payment');

            // Update siapa yang BUAT
            OrderItem::where('porder_id', $model->id)->update([
                'updated_by' => auth()->user()->id,
                'status' => $model->status,
                'date_order' => $model->date_order
            ]);

            // Update siapa yang JUAL/BELI di PAYMENT
            Payment::where('paymentable_id', $model->id)->where('paymentable_type', Porder::class)->update([
                'updated_by' => auth()->user()->id,
                'user_id' => $user_id,
            ]);

            // Tanggal Pelunasan
            if ($model->is_paid == 1) {
                $dataPAIDat = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $model->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('date_payment');
                if ($model->total_cashback >= 0) {
                    foreach ($model->payments as $payment) {
                        Payment::where('paymentable_type', Porder::class)
                            ->where('paymentable_id', $model->id)
                            ->where('mutation_type', "Purchase")
                            ->where('id', $payment->id)
                            ->update([
                                'nominal_plus' => 0,
                                'nominal' => $payment->nominal_mins - 0,
                                'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                                'kredit' => 'NR-DB-B-1100 CASH / BANK',
                            ]);
                    }
                    $nominalMins = Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $model->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->value('nominal_mins');
                    Payment::where('paymentable_type', Porder::class)->where('paymentable_id', $model->id)->where('mutation_type', "Purchase")->orderBy('date_payment', 'desc')->take(1)
                        ->update([
                            'nominal_plus' => $model->total_cashback,
                            'nominal' => $nominalMins - $model->total_cashback,
                            'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'kredit' => 'NR-DB-B-1100 CASH / BANK',
                        ]);
                }
            } else {
                $dataPAIDat = null;
                foreach ($model->payments as $payment) {
                    Payment::where('paymentable_type', Porder::class)
                        ->where('paymentable_id', $model->id)
                        ->where('mutation_type', "Purchase")
                        ->where('id', $payment->id)
                        ->update([
                            'nominal_plus' => 0,
                            'nominal' => $payment->nominal_mins - 0,
                            'debit' => 'NR-KR-C-2000 Hutang_Pembelian_Barang',
                            'kredit' => 'NR-DB-B-1100 CASH / BANK',
                        ]);
                }
            }

            // hitung berat dan ambil nilai beli untuk jurnal
            $barang_terbeli = 0;
            foreach ($model->items as $item) {
                $dataItem = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
                $dataItem->update(['total_weight' => $item->p_quantity * $produk_weight]);

                $barang_terbeli += Product::where('id', $dataItem->product_id)->value('cogs') * $item->p_quantity;
            }
            $sum_weight = OrderItem::where('porder_id', $model->id)->sum('total_weight');

            /// update barangterbeli
            Payment::where('paymentable_type', Porder::class)
                ->where('paymentable_id', $model->id)
                ->where('mutation_type', "Barang Terbeli")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $model->date_order,
                    'nominal_plus' => $barang_terbeli,
                    'nominal_mins' => 0,
                    'nominal' => $barang_terbeli,
                ]);

            $updatePaid = [
                'paid_at' => $dataPAIDat,
                'total_weight' => $sum_weight,
                'updated_by' => auth()->user()->id,
            ];
            $orderTarget->update($updatePaid);
        });
    }
}
