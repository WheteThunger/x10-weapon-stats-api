<?php namespace X10WeaponStatsApi\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use X10WeaponStatsApi\Console\Commands;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\ItemSchemaUpdate::class,
		Commands\ImportToDB::class,
        Commands\GenerateTf2ItemsFile::class
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
//		$schedule
//            ->command('inspire')
//            ->hourly();
	}

}
