<?php namespace X10WeaponStatsApi\Commands;

use X10WeaponStatsApi\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use X10WeaponStatsApi\Helpers\Tf2ItemsFile;

class ReadTf2ItemsFile extends Command implements SelfHandling {

    protected $file;
    
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($file)
	{
		$this->file = $file;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$tf2itemsfile = new Tf2ItemsFile();
		$tf2itemsfile->populateFromFile($this->file);
		$people = $tf2itemsfile->getPeople();
		
		dd($people);
	}

}
