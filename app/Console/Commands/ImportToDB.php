<?php namespace X10WeaponStatsApi\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use X10WeaponStatsApi\Models;
use X10WeaponStatsApi\Helpers\VDFTree\VDFTree;

use X10WeaponStatsApi\Commands\ImportFileToDB;

class ImportToDB extends Command
{

    use \Illuminate\Foundation\Bus\DispatchesCommands;

    protected $name = 'rxg-x10:import';

    protected $description = 'Write a tf2items.weapons.txt file to the master database';

    public function fire()
    {
        $start_time = microtime(true);
        $file = $this->argument('tf2items_file');
        $tree = new VDFTree($file);

        $config = Models\Config::where('name', '=', 'master')->get()->first();

        if (!$config instanceof Models\Config) {
            throw new \DomainException('Unable to locate master database');
        }

        $this->dispatch(new ImportFileToDB($tree, $config));

        $end_time = microtime(true);

        $duration = ($end_time - $start_time) * 1000;
        $duration = round($duration, 0);

        $this->info("Execution time: $duration ms");
    }

    protected function getArguments()
    {
        return [
            [
                'tf2items_file',
                InputArgument::REQUIRED,
                'path to the tf2items.weapons.txt file to write to the DB',
            ]
        ];
    }

    protected function getOptions()
    {
        return [];
    }
}