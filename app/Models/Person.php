<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {

    protected $primaryKey = 'id';

    protected $fillable = [
        'config_id',
        'name',
        'tf2items_id',
    ];

    public function weaponInstances()
    {
        return $this->hasMany('X10WeaponStatsApi\Models\WeaponInstance', 'person_id');
    }

}
