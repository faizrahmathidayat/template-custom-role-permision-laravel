<?php

namespace App\Models;

use App\Models\Settings\Menus;
use App\Models\Settings\Roles;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'menu_permissions';
    protected $guarded = [];

    public function menus()
    {
        return $this->belongsTo(Menus::class, 'menu_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }
}
