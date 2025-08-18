<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code_tr',
        'date_order',
        'user_id',
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
        static::updated(function ($model) {
            // Update siapa yang EDIT
            $dataInduk = Production::find($model->id);
            $dataInduk->update(['updated_by' => auth()->user()->id]);
            OrderItem::where('production_id', $model->id)->update(['updated_by' => auth()->user()->id, 'status' => $model->status, 'date_order' => $model->date_order]);

            foreach ($model->items as $item) {
                $data = OrderItem::find($item->id);
                $produk_weight = Product::where('id', $data->product_id)->value('weight');
                $data->update(['total_weight' => ($item->p_quantity - $item->quantity) * $produk_weight]);
            }
            $sum_weight = OrderItem::where('production_id', $model->id)->sum('total_weight');
            Production::where('id', $model->id)->update(['total_weight' => $sum_weight]);

            /// update jurnal PRODUKSI
            if ($dataInduk->grand_total >= 0) {
                $mutation_type = 'Barang Produksi Berkembang';
                $debit = 'NR-DB-B-2000 Persediaan Barang Dagang';
                $kredit = 'LR-KR-E-9200 Barang Produksi Mengembang';
                $nominal_plus = $dataInduk->grand_total;
                $nominal_mins = 0;
                $nominal = $dataInduk->grand_total;
            } else {
                $mutation_type = 'Barang Produksi Menyusut';
                $debit = 'LR-DB-F-1200 Barang Produksi Menyusut';
                $kredit = 'NR-DB-B-2000 Persediaan Barang Dagang';
                $nominal_plus = 0;
                $nominal_mins = abs($dataInduk->grand_total);
                $nominal = abs($dataInduk->grand_total);
            }
            Payment::where('paymentable_type', Production::class)
                ->where('paymentable_id', $model->id)
                ->update([
                    'date_payment' => $model->date_order,
                    'mutation_type' => $mutation_type,
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'nominal_plus' => $nominal_plus,
                    'nominal_mins' => $nominal_mins,
                    'nominal' => $nominal,
                ]);
        });
    }
}
