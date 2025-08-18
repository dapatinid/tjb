<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donate extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'cover',
        'body',
        'embed_videos',
        'type',
        'categories',
        'tags',
        'likes',
        'target',
        'collected',
        'date_from',
        'date_until',
        'date_published',
        'date_created',
        'updated_at',
        'created_by',
        'updated_by',
        'user_id',
        'branch_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            if ($model->isDirty('cover') && ($model->getOriginal('cover') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('cover'));
            }
        });
        static::deleted(function ($model) {
            if ($model->isForceDeleting()) {
                $directory = Str::of(storage_path('app/public/donates/' . Auth::user()->branch_id . '/' . Auth::user()->id . '/' . $model->date_created))->replace("/", '\\');
                File::deleteDirectory($directory);
            }
            if ($model->isForceDeleting()) {
                $model->payments()->forceDelete();
                $model->comments()->forceDelete();
            } else {
                $model->payments()->delete();
                $model->comments()->delete();
            }
        });
        static::restored(function ($model) {
            $model->payments()->restore();
            $model->comments()->restore();
        });
    }
}
