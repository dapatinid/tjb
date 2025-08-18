<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'image',
        'paymentable_id',
        'paymentable_type',
        'mutation_type',
        'debit',
        'kredit',
        'date_payment',
        'currency',
        'payment_method',
        'nominal_plus',
        'nominal_mins',
        'nominal',
        'rekening',
        'user_id',
        'created_by',
        'updated_by',
        'updated_at',
        'branch_id',
    ];

    protected $casts = [
        'date_payment' => 'datetime', // or 'date' if only date is needed
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userCre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function userUpd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::updated(function ($model) {
    //         $data = Payment::where('id', $model->id);
    //         $data->update([
    //             'nominal' => $model->nominal_plus - $model->nominal_mins,
    //         ]);
    //     });
    // }
}
