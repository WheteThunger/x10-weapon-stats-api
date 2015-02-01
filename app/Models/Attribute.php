<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {

    protected $primaryKey = 'defindex';
    
	protected $fillable = [
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
