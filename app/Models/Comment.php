<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'sub_id',
        'image',
        'body',
        'date_created',
        'updated_at',
        'created_by',
        'updated_by',
        'branch_id',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
