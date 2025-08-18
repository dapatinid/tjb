<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'cover',
        'body',
        'embed_videos',
        'categories',
        'tags',
        'likes',
        'date_published',
        'date_created',
        'updated_at',
        'created_by',
        'updated_by',
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
                $directory = Str::of(storage_path('app/public/posts/' . Auth::user()->branch_id . '/' . Auth::user()->id . '/' . $model->date_created))->replace("/", '\\');
                File::deleteDirectory($directory);
            }
            if ($model->isForceDeleting()) {
                $model->comments()->forceDelete();
            } else {
                $model->comments()->delete();
            }
        });
        static::restored(function ($model) {
            $model->comments()->restore();
        });
    }
}
