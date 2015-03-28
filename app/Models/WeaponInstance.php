<?php
namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponInstance extends Model
{

    protected $table = 'weapon_instance';

    protected $fillable = [
        'person_id',
        'weapon_defindex'
    ];

    //protected $appends = ['weapon_name'];
    
    public function weaponInstanceAttributes() {
        return $this->hasMany('X10WeaponStatsApi\Models\WeaponInstanceAttribute', 'weapon_instance_id');
    }

    public function person() {
        return $this->belongsTo('X10WeaponStatsApi\Models\Person', 'person_id');
    }

    public function weapon() {
        return $this->belongsTo('X10WeaponStatsApi\Models\Weapon', 'weapon_defindex');
    }

//     public function getWeaponNameAttribute() {
//         return $this->weapon()->first()->name;
//     }
    
}