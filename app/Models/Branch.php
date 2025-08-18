<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'logo',
        'name',
        'slug',
        'image',
        'phone',
        'street_address',
        'type',
        'name_partner',
        'is_open',
        'is_active',
        'created_by',
        'updated_by',
        'partner_id',
    ];

    protected $casts = [
        'type' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            if ($model->isDirty('image') && ($model->getOriginal('image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            if ($model->isDirty('logo') && ($model->getOriginal('logo') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('logo'));
            }
        });
    }
}
