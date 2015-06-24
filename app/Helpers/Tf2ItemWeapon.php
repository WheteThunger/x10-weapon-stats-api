<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 22/02/15
 * Time: 9:18 AM
 */

namespace X10WeaponStatsApi\Helpers;


use Illuminate\Support\Collection;

class Tf2ItemWeapon {

    public $defindex;

    public $value;

    public $comment;

    public function __construct($defindex, $value, $comment = null)
    {
        $this->defindex = $defindex;
        $this->value = $this->generateAttributeCollection($value);
        $this->comment = $comment;
    }

    protected function generateAttributeCollection(Collection $raw_value)
    {
        return $raw_value->map(function($attr) {
            $arr = explode(';', $attr->value);
            $arr = array_filter($arr, 'trim');
            list($defindex, $value) = $arr;

            return new Tf2ItemAttribute($defindex, $value, $attr->comment);
        });
    }

    public function printValue($depth = 0)
    {
    	return 'not implemented';
    }

}