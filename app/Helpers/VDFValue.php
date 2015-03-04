<?php
namespace X10WeaponStatsApi\Helpers;

use Illuminate\Support\Collection;

class VDFValue
{

    public $key;

    public $value;

    public $comment;

    public function __construct($key, $value, $comment = '')
    {
        $this->comment = $comment;
        $this->key = $key;

        if (is_scalar($value)) {
            $this->value = $value;
        } elseif (is_array($value)) {
            $this->value = new Collection();
            foreach ($value as $k => $v) {
                $this->value->push(new VDFValue($k, $v));
            }
        } else {
            throw new \Exception('who knows');
        }
    }

    public function printValue($depth = 0)
    {
        $tabs = str_repeat("    ", $depth);

        if (is_scalar($this->value)) {
            $comment = $this->comment ? " // $this->comment" : "";
            return "$tabs\"$this->key\"    \"$this->value\"$comment\n";
        } elseif ($this->value instanceof Collection) {
            $output = "";

            $output .= "$tabs\"$this->key\"\n";
            $output .= "$tabs{\n";

            foreach($this->value as $value)
            {
                $output .= $value->printValue($depth+1);
            }

            $output .= "$tabs}\n";

            return $output;
        } else {
            throw \Exception('value is not a scalar or an array');
        }
    }
}