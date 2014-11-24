<?php namespace Gas\Prices;

use Illuminate\Filesystem\Filesystem;

class UpdatePrices {

	protected $baseUrl = 'http://www6.mityc.es/aplicaciones/carburantes/';

	protected $namePrefix = 'eess_';

	protected $names = ['GPR', 'G98', 'GOA', 'NGO', 'GOB', 'GOC', 'BIO', 'G95', 'BIE', 'GLP', 'GNC'];

	protected $storagePath;

	protected $filesystem;

	function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
		$this->storagePath = storage_path('fuel_prices/');
	}


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

	protected function makePriceDirectory()
	{
		$this->filesystem->makeDirectory($this->storagePath);
	}

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