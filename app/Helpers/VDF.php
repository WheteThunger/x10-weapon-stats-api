<?php
namespace X10WeaponStatsApi\Helpers;

use Illuminate\Support\Collection;

class VDF extends Collection
{
    public function __construct($items = [])
    {
        if (count($items) !== 1) {
            throw new \Exception('A VDF file must have a single top level entry');
        }

        $keys = array_keys($items);
        $item = new VDFValue(array_shift($keys), array_shift($items));

        parent::__construct([$item]);
    }

    public static function makeFromVDFFile($file)
    {
        $data = static::parseVDFFile($file);
        return new static($data);
    }

    /*
     * The following code was taken from https://gist.github.com/AlienHoboken/5571903
     * and then modified
     * It is linked to in this stackoverflow answer suggesting it can be used
     * http://stackoverflow.com/a/21947808
     */
    protected static function parseVDFFile($file)
    {
        // load VDF data either from API call or fetching from file/url
        // no matter your method, $json must contain the VDF data to be parsed
        $json = file_get_contents($file);

        // encapsulate in braces
        $json = "{\n$json\n}";


        // remove comments
        $pattern = '~([^/]*)//.*~';
        $replace = '$1';
        $json = preg_replace($pattern, $replace, $json);

        // replace open braces
        $pattern = '/"([^"]*)"(\s*){/';
        $replace = '"${1}": {';
        $json = preg_replace($pattern, $replace, $json);

        // replace values
        $pattern = '~"([^"]*)"\s*"([^"]*)"(.*)~';
        $replace = '"${1}": "${2}",';
        $json = preg_replace($pattern, $replace, $json);

        // remove trailing commas
        $pattern = '/,(\s*[}\]])/';
        $replace = '${1}';
        $json = preg_replace($pattern, $replace, $json);

        // add commas
        $pattern = '/([}\]])(\s*)("[^"]*":\s*)?([{\[])/';
        $replace = '${1},${2}${3}${4}';
        $json = preg_replace($pattern, $replace, $json);

        // object as value
        $pattern = '/}(\s*"[^"]*":)/';
        $replace = '},${1}';
        $json = preg_replace($pattern, $replace, $json);

        $array = json_decode($json, true);

        if (!is_array($array)) {
            throw new \Exception('we don\'t have an array');
        }

        return $array;
    }


    public function generateVDFFile()
    {
        return trim($this->first()->printValue());
    }


    /*    private function aprintEntry($key, $entry, $depth = 0)
        {
            $output = "";
            $tabs = str_repeat("    ", $depth);

            if($entry instanceof VDFValue)
            {
                $output .= $entry->printValue($depth+1);
            }

            foreach ($entry as $key => $item) {
                if ($item instanceof Collection) {
                    $output .= "\n$tabs\"$key\"\n$tabs{\n";
                    $output .= $this->printEntry($key, $item, $depth + 1);
                    $output .= "\n$tabs}";
                } elseif($item instanceof VDFValue) {
                    $output .= "$tabs\"$item->key\"   \"$item->value\"\n";
                }
                else {
                    throw new \Exception('shouldn\'t get here');
                }
            }

            return $output;
        }
    */

    /*    public function printVDF()
        {
            return $this->item[0]->printValue();
        }
    */
}













