<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class TF2Class extends Model {

    protected $primaryKey = 'id';
    
    protected $table = 'classes';

    protected $fillable = [
        'name',
    ];

}
