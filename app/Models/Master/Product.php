<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'master_products';
    protected $guarded = [];

    public function product_category()
    {
        return $this->hasOne(ProductCategory::class,'id','product_category_id');
    }

    public function product_subcategory()
    {
        return $this->hasOne(ProductSubCategory::class,'id','product_subcategory_id');
    }

    public function product_brand()
    {
        return $this->hasOne(ProductBrand::class,'id','product_brand_id');
    }

    public function stock_unit()
    {
        return $this->hasOne(Unit::class,'id','stock_unit_name');
    }

    public function product_divisi()
    {
        return $this->hasOne(ProductDivisi::class,'id','product_divisi_id');
    }
}
