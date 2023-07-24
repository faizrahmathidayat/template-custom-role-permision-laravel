<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SyncJobsLogs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'sync_jobs_logs';
    protected $guarded = [];
}
