<?php namespace Gas\Commands;

use Gas\Prices\UpdatePrices;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdatePricesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'gas:update-prices';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fetch updated prices from mityc.es.';

	protected $updater;

	/**
	 * Create a new command instance.
	 *
	 * @param UpdatePrices $updater
	 * @return \Gas\Commands\UpdatePricesCommand
	 */
	public function __construct(UpdatePrices $updater)
	{
		parent::__construct();

		$this->updater = $updater;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info("Updating prices..");

		$this->updater->updatePriceList();

		$this->info("Prices Updated");
		$this->error("Error updating prices");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
