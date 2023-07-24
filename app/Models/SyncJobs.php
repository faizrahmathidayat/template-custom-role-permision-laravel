<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SyncJobs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'sync_jobs';
    protected $guarded = [];
}
