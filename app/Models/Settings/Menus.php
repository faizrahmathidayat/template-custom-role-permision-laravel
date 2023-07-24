<?php

namespace App\Models\Settings;

use App\Models\Permissions;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'menus';
    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(Permissions::class, 'menu_id', 'id');
    }

    public function sub_menus()
    {
        return $this->hasMany(Menus::class, 'parent_menu_id', 'id');
    }
}
