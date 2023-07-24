<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MappingExternalData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'mapping_external_data';
    protected $guarded = [];
}
