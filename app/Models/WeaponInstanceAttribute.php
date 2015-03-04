<?php

namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponInstanceAttribute extends Model
{
     
    protected $fillable = [
        'weapon_instance_id',
        'attribute_defindex',
        'attribute_value',
    ];
    
    public function weaponInstance()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\WeaponInstance');
    }

    public function attribute()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\Attribute', 'attribute_defindex');
    }
}