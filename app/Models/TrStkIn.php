<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TrStkIn extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'paymentable_id',
        'code_tr',
        'date_order',
        'user_id',
        'from_branch_id',
        'to_branch_id',
        'currency',
        'status',
        'notes',
        'total_weight',
        'grand_total',
        'created_by',
        'updated_by',
        'updated_at',
        'branch_id',
    ];

    public function branch()
    {
        return $this->hasMany(Branch::class);
    }
    public function from_branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function to_branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->update(['updated_by' => auth()->user()->id,]);
        });
        static::deleted(function ($model) {
            // if ($model->isForceDeleting()) {
            //     $model->items()->withTrashed()->forceDelete();
            // } else {
            $model->items()->delete();
            $model->payments()->delete();
            // }
        });
        static::restored(function ($model) {
            $model->items()->withTrashed()->restore();
        });
        static::updated(function ($model) {
            $dataOut = TrStkOut::withTrashed()->where('code_tr', Str::replace('TRI', 'TRO', $model->code_tr));
            $dataOutGrandTotal = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $model->code_tr))->value('grand_total');
            $dataIn = TrStkIn::withTrashed()->where('code_tr', $model->code_tr);
            if ($model->isDirty('status')) {
                $dataOut->update(['status' => $model->status, 'date_order' => now()]);
                $dataIn->update(['date_order' => now(), 'user_id' => auth()->user()->id, 'grand_total' => $dataOutGrandTotal]);
            }

            // update berat
            foreach ($model->items as $item) {
                $data = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $data->product_id)->value('weight');
                $data->update(['total_weight' => $item->p_quantity * $produk_weight]);
            }
            $sum_weight = OrderItem::where('tr_stk_in_id', $model->id)->sum('total_weight');
            TrStkIn::where('id', $model->id)->update(['total_weight' => $sum_weight]);

            /// update jurnal TRANSFER
            $id_Out = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $model->code_tr))->value('id');
            Payment::where('paymentable_type', TrStkOut::class)
                ->where('paymentable_id', $id_Out)
                ->update([
                    'date_payment' => $model->updated_at,
                    'mutation_type' => 'Barang Transfer Keluar',
                    'nominal_plus' => 0,
                    'nominal_mins' => abs($dataOutGrandTotal),
                    'nominal' => abs($dataOutGrandTotal),
                ]);

            if (Payment::where('paymentable_type', TrStkIn::class)->where('paymentable_id', $model->id)->count() > 0) {
                Payment::where('paymentable_type', TrStkIn::class)
                    ->where('paymentable_id', $model->id)
                    ->update([
                        'date_payment' => $model->updated_at,
                        'mutation_type' => 'Barang Transfer Masuk',
                        'debit' => 'NR-DB-B-2000 Persediaan Barang Dagang',
                        'kredit' => 'NR-KR-D-3500 Barang Transfer',
                        'nominal_plus' => abs($dataOutGrandTotal),
                        'nominal_mins' => 0,
                        'nominal' => abs($dataOutGrandTotal),
                        'user_id' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                    ]);
            } else {
                $barangtransferIn = new Payment();
                $barangtransferIn->paymentable_id = $model->id;
                $barangtransferIn->paymentable_type = 'App\Models\TrStkIn';
                $barangtransferIn->date_payment = date('Y-m-d H:i:s');
                $barangtransferIn->currency = 'idr';
                $barangtransferIn->mutation_type = 'Barang Transfer Masuk';
                $barangtransferIn->debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
                $barangtransferIn->kredit = 'NR-KR-D-3500 Barang Transfer';
                $barangtransferIn->nominal_plus = abs($dataOutGrandTotal);
                $barangtransferIn->nominal_mins = 0;
                $barangtransferIn->nominal = abs($dataOutGrandTotal);
                $barangtransferIn->user_id = $model->user_id;
                $barangtransferIn->created_by = auth()->user()->id;
                $barangtransferIn->updated_by = auth()->user()->id;
                $barangtransferIn->branch_id  = auth()->user()->branch_id;
                $barangtransferIn->save();
            }

            // Update HPP tiap produk ketika melakukan Transfer In
            // $record = $this->record;
            $dataBelanja = OrderItem::where('tr_stk_in_id', $model->id)->get();
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
                $hppBaru = $item->unit_amount;
                $hppAvg = (($hppLama * $stokBefore) + ($hppBaru * $stokAdd)) / $stokAfter;
                $update = ['cogs' => $hppAvg];
                $itemUpdate->update($update);
            }

            // Update yang Edit di ORDERITEM dan INVOICE
            TrStkIn::where('id', $model->id)->update(['user_id' => auth()->user()->id, 'updated_by' => auth()->user()->id]);
            $outID = TrStkOut::where('code_tr', Str::replace('TRI', 'TRO', $model->code_tr))->value('id');
            OrderItem::where('tr_stk_in_id', $model->id)->update(['updated_by' => auth()->user()->id, 'status' => $model->status, 'date_order' => $model->date_order]);
            OrderItem::where('tr_stk_out_id', $outID)->update(['updated_by' => auth()->user()->id, 'status' => $model->status, 'date_order' => $model->date_order]);
        });
    }
}
