<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;


class Warehouse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'master_warehouses';
    protected $guarded = [];
}
