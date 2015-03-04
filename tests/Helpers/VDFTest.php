<?php

require __DIR__ . '/../TestCase.php';

use X10WeaponStatsApi\Helpers\VDF;

class VDFTest extends TestCase
{


    public function testReadWriteSingleEntry()
    {
        $vdf = VDF::makeFromVDFFile(__DIR__ . '/vdf_files/single_entry.vdf');

        $normalized_output = $this->normalizeOutput($vdf->generateVDFFile());
        
        $contents = file_get_contents(__DIR__ . '/vdf_files/single_entry.vdf');
        $correct_output = $this->normalizeOutput($contents);
        
        $this->assertEquals($correct_output, $normalized_output);
    }

    public function testReadWriteLargeFile()
    {
        $vdf = VDF::makeFromVDFFile(__DIR__ . '/vdf_files/larger.vdf');

        $normalized_output = $this->normalizeOutput($vdf->generateVDFFile());

        $contents = file_get_contents(__DIR__ . '/vdf_files/larger.vdf');
        $correct_output = $this->normalizeOutput($contents);

        $this->assertEquals($correct_output, $normalized_output);
    }

    public function testGenerateFromArray()
    {
        $vdf = new VDF([
            'test_key' => [
                'sub_key' => '1',
                'sub_key2' => [
                    'qwe' => '3',
                    'asdf' => 'add'
                ],
                'sub_key3' => '9'
            ]
        ]);
        
        $str = $vdf->generateVDFFile();
        $normalized_output = $this->normalizeOutput($str);
        $correct_output = $this->normalizeOutput(
'"test_key"
{
    "sub_key" "1"
    "sub_key2"
    {
        "qwe" "3"
        "asdf" "add"
    }
    "sub_key3" "9"
}'
        );
        
        $this->assertEquals($correct_output, $normalized_output);
    }



    public function testReadWriteExampleTf2itemsFile()
    {
        $vdf = VDF::makeFromVDFFile(__DIR__ . '/vdf_files/example_tf2items.weapons.txt');

        $normalized_output = $this->normalizeOutput($vdf->generateVDFFile());

        $contents = file_get_contents(__DIR__ . '/vdf_files/example_tf2items.weapons.txt');
        $correct_output = $this->normalizeOutput($contents);

        $this->assertEquals($correct_output, $normalized_output);
    }

    private function normalizeOutput($str)
    {
        $regexes = [
            '~([^/]*)//.*~' => '$1', // remove comments
            '~"([^"]*)"\s*"([^"]*)"(.*)~' => '"${1}": "${2}",', // Grab values
            '~[\s\n\t]+~' => "\n", // Replace all spaces with a new line for easier diffing
            '~"141"
{
"1":
"136
;
1",
"2":
"15
;
1",
"3":
"3
;
0.75",
}
~' => '' // There is an error in the example tf2items file. It references 141 twice, so were just removing it.
        ];

        foreach($regexes as $pattern => $replace) {
            $str = preg_replace($pattern, $replace, $str);
        }

        return trim($str);
    }

}