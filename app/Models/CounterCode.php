<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CounterCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'counter_codes';
    protected $guarded = [];
}
