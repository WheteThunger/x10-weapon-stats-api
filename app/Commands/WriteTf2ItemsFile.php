<?php namespace X10WeaponStatsApi\Commands;

use X10WeaponStatsApi\Commands\Command;

use Illuminate\Contracts\Bus\SelfHandling;

use X10WeaponStatsApi\Helpers\Tf2ItemsFile;

class WriteTf2ItemsFile extends Command implements SelfHandling {

    protected $data;
    
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$tf2itemsfile = new Tf2ItemsFile();
		$tf2itemsfile->populateFromArray($this->data);
		$file_contents = $tf2itemsfile->generateFile();
		
		dd($file_contents);
	}

}
