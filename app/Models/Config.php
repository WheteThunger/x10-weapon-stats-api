<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

    const MASTER_NAME = "master";

    protected $primaryKey = 'id';
    
	protected $fillable = [
	    'name',
	    'parent_id'
	];

    public function people()
    {
        return $this->hasMany('X10WeaponStatsApi\Models\Person', 'config_id');
    }

}
