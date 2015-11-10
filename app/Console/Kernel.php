<?php namespace X10WeaponStatsApi\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
//		\X10WeaponStatsApi\Console\Commands\Inspire::class,
		\X10WeaponStatsApi\Console\Commands\ItemSchemaUpdate::class,
//		\X10WeaponStatsApi\Console\Commands\Tf2ItemsReadFile::class,
//		\X10WeaponStatsApi\Console\Commands\Tf2ItemsWriteFile::class,
		\X10WeaponStatsApi\Console\Commands\ImportToDB::class

	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();
	}

}
