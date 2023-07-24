<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;


class ProductionCapacity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'production_capacities';
    protected $guarded = [];

    public function subcategory()
    {
        return $this->hasOne(ProductSubCategory::class,'id','subcategory_id');
    }
}
