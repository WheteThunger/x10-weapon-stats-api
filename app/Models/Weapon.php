<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model {

    protected $primaryKey = 'defindex';
    
    // I'm not sure if this "should" be public. 
	public $fillable = [
	    'defindex',
	    'item_class',
	    'item_type_name',
	    'item_name',
	    'item_description',
	    'proper_name',
	    'item_slot',
	    'item_quality',
	    'image_url',
	    'image_url_large',
	    'min_ilevel',
	    'max_ilevel'
	];

}
