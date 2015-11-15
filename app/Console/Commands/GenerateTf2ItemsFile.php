<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use X10WeaponStatsApi\Commands;
use X10WeaponStatsApi\Models\Config;

class GenerateTf2ItemsFile extends Command {

    use \Illuminate\Foundation\Bus\DispatchesCommands;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'rxg-x10:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a tf2items.weapons.txt file from the database';

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
        $config_name = $this->argument("config");

        try {
            $config = Config::where("name", "=", $config_name)->firstOrFail();

            $command = new Commands\GenerateTf2ItemsFile($config);

            $this->dispatch($command);
        } catch(ModelNotFoundException $e) {
            $this->error("Could not find the config: $config_name");
        }
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
                "config",
                InputArgument::REQUIRED,
                "name of the config (server) to generate the file for"
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
