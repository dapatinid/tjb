<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku',
        'name',
        'variant',
        'alias',
        'slug',
        'unit_name',
        'weight',
        'contain',
        'description',
        'images',
        'embed_videos',

        'branch_id',
        'category_id',
        'brand_id',
        'tags',

        'cogs',
        'price',
        'strikethroughprice',
        'poin',

        'low_alert',
        'is_active',
        'in_stock',
        'is_featured',
        'promo',
        'rating',

        'created_by',
        'updated_by',

        'updated_at',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function pquantities()
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id', 'id');
    // }
    // public function quantities()
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id', 'id');
    // }

    // public function ptquantities()
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id', 'id');
    // }
    // public function tquantities()
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id', 'id');
    // }


    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            $imagesBefore = Product::where('id', $model->id)->value('images');
            if ($model->isDirty('images') && ($model->getOriginal('images') !== null)) {
                $imagesAfter = $model->images;
                $imagesDIFF = array_diff($imagesBefore, $imagesAfter);
                foreach ($imagesDIFF as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
        });
    }
}
