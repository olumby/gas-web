<?php namespace Gas\Prices;

use Illuminate\Support\ServiceProvider;

class PricesServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('prices', function()
		{
			//dd($this->app['config']);
			return new Prices($this->app['config']);
		});
	}
}