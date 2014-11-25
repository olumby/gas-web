<?php namespace Gas\Prices;

use Gas\Exceptions\NameNotFoundException;
use Gas\Exceptions\PriceFileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as Config;

class Prices {

	/**
	 * Fuel names.
	 *
	 * @var mixed
	 */
	protected $names;

	/**
	 * Config instance.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Config instance.
	 *
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @param Config $config
	 */
	function __construct(Config $config)
	{
		$this->config = $config;
		$this->filesystem = new Filesystem();
		$this->names = $config->get('fuel.names');
	}


	function get($name)
	{
		if (!in_array($name, $this->names))
		{
			throw new NameNotFoundException("Fuel price with that name does not exist.");
		}

		$storagePath = $this->config->get('fuel.storage_path');
		$filePath = $storagePath . $name . ".json";

		if(!$this->filesystem->exists($filePath))
		{
			throw new PriceFileNotFoundException("Price file not found for that name.");
		}

		return $this->filesystem->get($filePath);

	}

	function fuelNames()
	{
		return $this->names;
	}

} 