<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
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
    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shipping_method');
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
            // hitung Piutang Penjualan untuk jurnal
            // if ($model->isDirty('grand_total')) {
            Payment::where('paymentable_type', Order::class)
                ->where('paymentable_id', $model->id)
                ->where('mutation_type', "Piutang Penjualan")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $model->date_order,
                    'nominal_plus' => $model->grand_total,
                    'nominal_mins' => 0,
                    'nominal' => $model->grand_total,
                ]);
            // }
        });
        static::updated(function ($model) {
            $orderTarget = Order::where('id', $model->id);
            $user_id = Order::where('id', $model->id)->value('user_id');
            // $lastEdit = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $model->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('date_payment');

            // Update siapa yang BUAT
            OrderItem::where('order_id', $model->id)->update([
                'updated_by' => auth()->user()->id,
                'status' => $model->status,
                'date_order' => $model->date_order
            ]);

            // Update siapa yang JUAL/BELI di PAYMENT
            Payment::where('paymentable_id', $model->id)->where('paymentable_type', Order::class)->update([
                'updated_by' => auth()->user()->id,
                'user_id' => $user_id,
            ]);

            // Tanggal Pelunasan
            if ($model->is_paid == 1) {
                $dataPAIDat = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $model->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('date_payment');
                if ($model->total_cashback >= 0) {
                    foreach ($model->payments as $payment) {
                        Payment::where('paymentable_type', Order::class)
                            ->where('paymentable_id', $model->id)
                            ->where('mutation_type', "Sales")
                            ->where('id', $payment->id)
                            ->update([
                                'nominal_mins' => 0,
                                'nominal' => $payment->nominal_plus - 0,
                                'debit' => 'NR-DB-B-1100 CASH / BANK',
                                'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                            ]);
                    }
                    $nominalPlus = Payment::where('paymentable_type', Order::class)->where('paymentable_id', $model->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->value('nominal_plus');
                    Payment::where('paymentable_type', Order::class)->where('paymentable_id', $model->id)->where('mutation_type', "Sales")->orderBy('date_payment', 'desc')->take(1)
                        ->update([
                            'nominal_mins' => $model->total_cashback,
                            'nominal' => $nominalPlus - $model->total_cashback,
                            'debit' => 'NR-DB-B-1100 CASH / BANK',
                            'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                        ]);
                }
            } else {
                $dataPAIDat = null;
                foreach ($model->payments as $payment) {
                    Payment::where('paymentable_type', Order::class)
                        ->where('paymentable_id', $model->id)
                        ->where('mutation_type', "Sales")
                        ->where('id', $payment->id)
                        ->update([
                            'nominal_mins' => 0,
                            'nominal' => $payment->nominal_plus - 0,
                            'debit' => 'NR-DB-B-1100 CASH / BANK',
                            'kredit' => 'NR-DB-B-3000 Piutang Penjualan Barang',
                        ]);
                }
            }

            // hitung berat dan ambil nilai beli untuk jurnal
            $barang_terjual = 0;
            foreach ($model->items as $item) {
                $dataItem = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $dataItem->product_id)->value('weight');
                $dataItem->update(['total_weight' => $item->quantity * $produk_weight]);

                $barang_terjual += Product::where('id', $dataItem->product_id)->value('cogs') * $item->quantity;
            }
            $sum_weight = OrderItem::where('order_id', $model->id)->sum('total_weight');

            /// update barangterjual
            Payment::where('paymentable_type', Order::class)
                ->where('paymentable_id', $model->id)
                ->where('mutation_type', "Barang Terjual")
                ->update([
                    // 'date_payment' => $lastEdit,
                    'date_payment' => $model->date_order,
                    'nominal_plus' => 0,
                    'nominal_mins' => $barang_terjual,
                    'nominal' => $barang_terjual,
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
