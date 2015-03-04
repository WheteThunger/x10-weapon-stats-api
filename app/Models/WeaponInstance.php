<?php
namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponInstance extends Model
{

    protected $table = 'weapon_instance';

    protected $fillable = [
        'config_id',
        'person_id',
        'weapon_defindex'
    ];

    public function weaponInstanceAttributes()
    {
        return $this->hasMany('X10WeaponStatsApi\Models\WeaponInstanceAttribute', 'weapon_instance_id');
    }

    public function config()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\Config');
    }

    public function person()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\Person');
    }

    public function weapon()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\Weapon', 'weapon_defindex');
    }

}