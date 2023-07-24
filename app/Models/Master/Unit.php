<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;


class Unit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'master_units';
    protected $guarded = [];
}
