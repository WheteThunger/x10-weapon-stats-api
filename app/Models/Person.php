<?php namespace X10WeaponStatsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'tf2items_id',
    ];

}
