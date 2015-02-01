<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

    protected $primaryKey = 'id';
    
	protected $fillable = [
	    'name',
	    'parent_id'
	];

}
