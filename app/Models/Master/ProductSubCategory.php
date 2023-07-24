<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;


class ProductSubCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'master_product_subcategories';
    protected $guarded = [];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id','category_id');
    }
}
