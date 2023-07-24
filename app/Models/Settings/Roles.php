<?php

namespace App\Models\Settings;

use App\Models\Permissions;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Roles extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'roles';
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id', 'id');
    }
}
