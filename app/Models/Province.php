<?php

namespace App\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Province extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * Model Constructor
     *
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('wilayah.table_names.province') ?? $this->table;
    }

    /**
     * Cities of this province
     */
    public function cities(): HasMany
    {
        return $this->hasMany(
            City::class,
            'province_code',
            'code'
        );
    }

    /**
     * Districts of this province
     */
    public function districts(): HasManyThrough
    {
        return $this->hasManyThrough(
            District::class,
            City::class,
            'province_code',
            'city_code',
            'code',
            'code'
        );
    }
}
