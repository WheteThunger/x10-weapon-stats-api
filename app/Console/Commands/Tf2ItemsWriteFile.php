<?php
namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use X10WeaponStatsApi\Commands\WriteTf2ItemsFile;

class Tf2ItemsWriteFile extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tf2items:write';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temporary command so I have something to debug while writing the writer';

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
        $data = [];
		$result = \Bus::dispatch(new WriteTf2ItemsFile($data));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
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
