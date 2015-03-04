<?php

namespace X10WeaponStatsApi\Helpers;


class Tf2ItemAttribute {

    public $defindex;

    public $value;

    public $comment;

    public function __construct($defindex, $value, $comment = null)
    {
        $this->defindex = trim($defindex);
        $this->value = trim($value);
        $this->comment = trim($comment);
    }

}