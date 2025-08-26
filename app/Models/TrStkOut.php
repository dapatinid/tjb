<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TrStkOut extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
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
            //     $dataIn = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr));
            //     $itemIn = OrderItem::where('tr_stk_in_id', $dataIn->value('id'));
            //     $itemIn->forceDelete();
            //     $dataIn->forceDelete();
            //     $model->items()->withTrashed()->forceDelete();
            // } else {
            $dataIn = TrStkIn::withTrashed()->where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr));
            $payIn = Payment::withTrashed()->where('paymentable_type', TrStkIn::class)->where('paymentable_id', $dataIn->value('id'));
            $itemIn = OrderItem::withTrashed()->where('tr_stk_in_id', $dataIn->value('id'));
            $itemIn->delete();
            $payIn->delete();
            $dataIn->delete();
            $model->items()->delete();
            $model->payments()->delete();
            // }
        });
        static::restored(function ($model) {
            $model->items()->withTrashed()->restore();
            $dataIn = TrStkIn::withTrashed()->where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr));
            $itemIn = OrderItem::withTrashed()->where('tr_stk_in_id', $dataIn->value('id'));
            $dataIn->restore();
            $itemIn->restore();
        });
        static::updating(function ($model) {
            $tr_stk_in_id = Str::replace('TRO', 'TRI', $model->code_tr);
            $id_In = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr))->value('id');
            Payment::where('paymentable_type', TrStkIn::class)->where('paymentable_id', $id_In)->forceDelete();
            TrStkIn::where('code_tr', $tr_stk_in_id)->forceDelete();

            $dataIn = TrStkIn::withTrashed()->where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr));
            $dataOut = TrStkOut::withTrashed()->where('code_tr', $model->code_tr);
            if ($model->isDirty('status')) {
                $dataIn->update(['status' => $model->status]);
                $dataOut->update(['user_id' => auth()->user()->id]);
            }
            // update berat
            foreach ($model->items as $item) {
                $data = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $data->product_id)->value('weight');
                $data->update(['total_weight' => $item->quantity * $produk_weight]);
            }
            $sum_weight = OrderItem::where('tr_stk_out_id', $model->id)->sum('total_weight');
            TrStkOut::where('id', $model->id)->update(['total_weight' => $sum_weight, 'updated_by' => auth()->user()->id]);
        });
        static::updated(function ($model) {
            /// update jurnal TRANSFER
            Payment::where('paymentable_type', TrStkOut::class)
                ->where('paymentable_id', $model->id)
                ->update([
                    'date_payment' => $model->updated_at,
                    'mutation_type' => 'Barang Transfer Keluar',
                    'debit' => 'NR-KR-D-2500 Barang Transfer',
                    'kredit' => 'NR-DB-B-2000 Persediaan Barang Dagang',
                    'nominal_plus' => 0,
                    'nominal_mins' => abs($model->grand_total),
                    'nominal' => abs($model->grand_total),
                ]);
            // $id_In = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr))->value('id');
            // Payment::where('paymentable_type', TrStkIn::class)
            //     ->where('paymentable_id', $id_In)
            //     ->update([
            //         'paymentable_id' => $id_In,
            //         'date_payment' => $model->updated_at,
            //         'mutation_type' => 'Barang Transfer Masuk',
            //         'nominal_plus' => abs($model->grand_total),
            //         'nominal_mins' => 0,
            //         'nominal' => abs($model->grand_total),
            //     ]);


            // $record = $this->record;
            TrStkOut::where('code_tr', $model->code_tr)->update(['status' => 'new']);

            $tfIN = new TrStkIn();
            $tfIN->branch_id = $model->to_branch_id;
            $tfIN->user_id = $model->user_id;
            $tfIN->created_by = $model->created_by;
            $tfIN->updated_by = $model->updated_by;
            $tfIN->code_tr = Str::replace('TRO', 'TRI', $model->code_tr);
            $tfIN->from_branch_id = $model->from_branch_id;
            $tfIN->to_branch_id = $model->to_branch_id;
            $tfIN->date_order = $model->date_order;
            // $tfIN->user_id = $model->user_id;
            $tfIN->currency = $model->currency;
            $tfIN->status = 'new';
            $tfIN->notes = $model->notes;
            // $tfIN->grand_total = $model->grand_total;
            $tfIN->created_at = $model->created_at;
            $tfIN->updated_at = $model->updated_at;
            $tfIN->save();

            // CabangPenerima START
            $ItemFrom = $model->items;
            foreach ($ItemFrom as $item) {
                $produkdari = Product::where('branch_id', $model->from_branch_id);
                $produk = Product::where('branch_id', $model->to_branch_id);
                $sku = $produkdari->where('id', $item['product_id'])->value('sku');
                $produkID = $produk->where('sku', $sku)->value('id');
                $orderitems = OrderItem::leftJoin('orders', 'order_items.id', '=', 'orders.id')->leftJoin('porders', 'order_items.id', '=', 'porders.id')->get()->where('order.status', '!=', 'canceled')->where('porder.status', '!=', 'canceled');
                $boughtqty = $orderitems->where('product_id', $produkID)->sum('p_quantity');
                $soldqty = $orderitems->where('product_id', $produkID)->sum('quantity');
                $stokBef = $boughtqty - $soldqty;
                $stokAft = $boughtqty - $soldqty + $item['quantity'];
                $tfIN->items()->saveMany([
                    new OrderItem([
                        'product_id' => $produkID,
                        'stock_before' => $stokBef,
                        'stock_after' => $stokAft,
                        'unit_name' => $produk->where('sku', $sku)->value('unit_name'),
                        'contain' => $produk->where('sku', $sku)->value('contain'),
                        'branch_id' => $produk->where('sku', $sku)->value('branch_id'),
                        'p_quantity' => $item['quantity'],
                        'unit_amount' => $item['p_unit_amount'],
                        'total_amount' => $item['p_total_amount'],
                        'notes' => $item['notes'],
                        'mutation_type' => 'Transfer In',
                        'created_by' => auth()->user()->id,
                    ]),
                ]);
            }

            $barangtransferIn = new Payment();
            $barangtransferIn->paymentable_id = $tfIN->id;
            $barangtransferIn->paymentable_type = 'App\Models\TrStkIn';
            $barangtransferIn->date_payment = $model->date_order;
            $barangtransferIn->currency = 'idr';
            $barangtransferIn->mutation_type = 'Barang Transfer Masuk';
            $barangtransferIn->debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
            $barangtransferIn->kredit = 'NR-KR-D-2500 Barang Transfer';
            $barangtransferIn->nominal_plus = abs($model->grand_total);
            $barangtransferIn->nominal_mins = 0;
            $barangtransferIn->nominal = abs($model->grand_total);
            $barangtransferIn->user_id = $tfIN->user_id;
            $barangtransferIn->created_by = auth()->user()->id;
            $barangtransferIn->updated_by = auth()->user()->id;
            $barangtransferIn->branch_id  = $tfIN->branch_id;
            $barangtransferIn->save();

            // Update siapa yang EDIT
            OrderItem::where('tr_stk_out_id', $model->id)->update(['created_by' => auth()->user()->id, 'updated_by' => auth()->user()->id, 'status' => $model->status, 'date_order' => $model->date_order]);
            $inID = TrStkIn::where('code_tr', Str::replace('TRO', 'TRI', $model->code_tr))->value('id');
            OrderItem::where('tr_stk_in_id', $inID)->update(['updated_by' => auth()->user()->id, 'status' => $model->status, 'date_order' => $model->date_order]);

            $tr_stk_out_id = $model->id;
            OrderItem::where('tr_stk_out_id', $tr_stk_out_id)->onlyTrashed()->forceDelete();
        });
    }
}
