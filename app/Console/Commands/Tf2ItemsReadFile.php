<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use X10WeaponStatsApi\Commands\ReadTf2ItemsFile;

use X10WeaponStatsApi\Helpers\VDF;

class Tf2ItemsReadFile extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tf2items:read';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Read a tf2items.weapons.txt file';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
	    $file = $this->argument('tf2items_file');
		$collection = VDF::makeFromVDFFile($file);
		
		$output = $collection->generateVDFFile();
		dd($output);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
		    [
		        'tf2items_file', 
                InputArgument::REQUIRED, 
                'path to the tf2items.weapons.txt file to read',
		    ]
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
