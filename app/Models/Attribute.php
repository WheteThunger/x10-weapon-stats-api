<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {

    protected $primaryKey = 'defindex';
    
    // I'm not sure if this "should" be public. 
	public $fillable = [
		'defindex',
		'name',
		'attribute_class',
		'description_string',
		'description_format',
		'effect_type',
		'hidden',
		'stored_as_integer'
	];

}
