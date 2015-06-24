<?php

namespace X10WeaponStatsApi\Helpers;

use Illuminate\Support\Collection;

class Tf2ItemParty {

    public $party;

    public $value;

    public $comment;

    public function __construct(array $party, $value, $comment = null)
    {
        $this->party = $party;
        $this->value = $this->generateWeaponCollection($value);
        $this->comment = $comment;
    }

    protected function generateWeaponCollection(Collection $raw_value)
    {
        return $raw_value->map(function($weapon) {
            return new Tf2ItemWeapon($weapon->key, $weapon->value, $weapon->comment);
        });
    }

    public function printValue($depth = 0)
    {
        $tabs = str_repeat("    ", $depth);
        $party_str = implode(' ; ', $this->party);

        $comment_str = $this->comment ? "// $this->comment" : "";

        $value_str = 'not implemented';

        foreach($this->value as $weapon) {
			$value_str += $weapon->defindex;
        }


        $output = <<<EOT
$tabs"$party_str" $comment_str
$tabs{
$tabs{$value_str}
$tabs}
EOT;

        return $output;
    }



}