<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model {

    protected $primaryKey = 'defindex';
    
	protected $fillable = [
	    'defindex',
	    'name',
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
	
	public function classes() {
	    return $this->belongsToMany('X10WeaponStatsApi\Models\TF2Class', 'class_weapon','weapon_defindex', 'class_id');
	}

}
