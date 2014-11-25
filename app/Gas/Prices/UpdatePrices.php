<?php namespace Gas\Prices;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as Config;

class UpdatePrices {

	/**
	 * Base url where fuel prices are located.
	 *
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * Name prefix for fuel price files.
	 *
	 * @var string
	 */
	protected $namePrefix;

	/**
	 * Different types of fuel prices.
	 *
	 * @var array
	 */
	protected $names;

	/**
	 * Storage location for app files.
	 *
	 * @var string
	 */
	protected $storagePath;

	/**
	 * Filesystem instance.
	 *
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * Config instance.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * @param Config     $config
	 * @param Filesystem $filesystem
	 */
	function __construct(Config $config, Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
		$this->config = $config;

		$this->names = $config->get('fuel.names');
		$this->namePrefix = $config->get('fuel.name_prefix');
		$this->baseUrl = $config->get('fuel.base_url');
		$this->storagePath = $config->get('fuel.storage_path');
	}


	/**
	 * Fetch the latest fuel prices and replace old ones.
	 */
	function updatePriceList()
	{
		if (!$this->priceDirectoryExists())
		{
			$this->makePriceDirectory();
		}

		foreach ($this->names as $name)
		{
			$filename = $name . '.zip';
			$storeFilename = $this->storagePath . $filename;

			$remoteUrl = $this->baseUrl . $this->namePrefix . $filename;
			$zipFile = file_get_contents($remoteUrl);

			$this->filesystem->put($storeFilename, $zipFile);

			$this->extractZip($storeFilename);
			$this->convertCsvToJson($name);
		}
	}

	/**
	 * Creates the storage directory for the prices.
	 */
	protected function makePriceDirectory()
	{
		$this->filesystem->makeDirectory($this->storagePath);
	}

	/**
	 * Checks to see if the fuel price directory exists.
	 *
	 * @return bool
	 */
	protected function priceDirectoryExists()
	{
		if (!$this->filesystem->exists($this->storagePath) || !$this->filesystem->isDirectory($this->storagePath))
		{
			return false;
		}

		return true;
	}

	/**
	 * Extract downloaded zip file.
	 *
	 * @param $storeFilename
	 */
	protected function extractZip($storeFilename)
	{
		$zipper = new \ZipArchive();
		$zipper->open($storeFilename);
		$zipper->extractTo($this->storagePath);
		$zipper->close();

		$this->filesystem->delete($storeFilename);
	}

	/**
	 * Convert extracted csv file to json.
	 *
	 * @param $name
	 */
	protected function convertCsvToJson($name)
	{
		$csvFilename = $this->storagePath . $this->namePrefix . $name . ".csv";
		$csvFile = file_get_contents($csvFilename);

		$csvLines = array_map('str_getcsv', file($csvFilename));
		array_shift($csvLines);
		array_shift($csvLines);

		$jsonArray = json_encode($csvLines);

		$jsonFilename = $this->storagePath . $name . ".json";
		$this->filesystem->put($jsonFilename, $jsonArray);

		$this->filesystem->delete($csvFilename);
	}

} 