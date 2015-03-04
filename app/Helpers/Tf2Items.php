<?php
namespace X10WeaponStatsApi\Helpers;

use Illuminate\Support\Collection;

class Tf2Items extends Collection
{

    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public static function makeFromVDFFile($file)
    {
        $vdf_instance = VDF::makeFromVDFFile($file);

        //dd($vdf_instance);

        $data = static::generatePartyCollection($vdf_instance)->toArray();

        return new static($data);
    }

    protected static function generatePartyCollection($vdf_instance)
    {
        $data = new Collection();
        foreach ($vdf_instance->first()->value as $raw_party) {
            $party = $raw_party->key;

            if ($party === '*') {
                $party = ['*'];
            } else {
                $party = explode(';', $party);
                $party = array_filter($party, 'trim');
            }
            $data->push(new Tf2ItemParty($party, $raw_party->value));
        }

       return $data;
    }

    public function generateVDFFile()
    {
        $printValue = trim($this->first()->printValue(1));

        $output = <<<EOT
"custom_weapons_v3"
{
$printValue
}
EOT;

        return $output;
    }

}
