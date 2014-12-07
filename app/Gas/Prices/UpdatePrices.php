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

		$forJson = [];
		$forGeoJson = [
			'type'     => 'FeatureCollection',
			'features' => []
		];

		foreach ($csvLines as $line)
		{
			if (!isset($line[0]) || !isset($line[1]) || !isset($line[2]))
				continue;

			$stLong = trim($line[1]);
			$stLat = trim($line[0]);
			$stDetails = trim($line[2]);

			$extractedDetails = $this->extractNameDetails($stDetails);

			$stPrice = str_replace(',', '.', $extractedDetails['price']);
			$stName = $extractedDetails['name'];
			$stHours = $extractedDetails['hours'];

			$forJson[] = [
				'lat'   => (float)$stLat,
				'long'  => (float)$stLong,
				'name'  => $stName,
				'hours' => $stHours,
				'price' => (float)$stPrice
			];

			$forGeoJson['features'][] = [
				'geometry' => [
					'type' => 'Point',
					'coordinates' => [
						(float)$stLat,
						(float)$stLong
					]
				],
				'type' => 'Feature',
				'properties' => [
					'name' => $stName,
					'hours' => $stHours,
					'price' => (float)$stPrice
				]
			];
		}

		$jsonArray = json_encode($forJson);
		$geoJsonArray = json_encode($forGeoJson);

		$jsonFilename = $this->storagePath . $name . ".json";
		$this->filesystem->put($jsonFilename, $jsonArray);

		$geoJsonFilename = $this->storagePath . $name . ".geojson";
		$this->filesystem->put($geoJsonFilename, $geoJsonArray);

		$this->filesystem->delete($csvFilename);
	}

	/**
	 * @param $details
	 * @return mixed
	 */
	protected function extractNameDetails($details)
	{
		$response = [];

		if (strpos($details, 'Horario Especial') !== false)
		{
			/*
			 * REPSOL Horario Especial 0,928 e
			 * Name   Hours            Price
			 * (.*) Horario Especial (\d,\d*) e
			 */

			preg_match('/(.*) Horario Especial (\d,\d*) e/', $details, $matches);

			$response['hours'] = "Horario Especial";
			$response['name'] = $matches[1];
			$response['price'] = $matches[2];

			return $response;

		}

		/*
		 * PETROMIRALLES L-D: 24H 0,999 e
		 * Name          Hours    Price
		 * GALP L-D: 24H 0,929 e
		 * Name Hours    Price
		 */

		preg_match('/(.*) (.*: .*) (\d,\d*) e/', $details, $matches);

		$response['name'] = $matches[1];
		$response['hours'] = $matches[2];
		$response['price'] = $matches[3];

		return $response;
	}

} 