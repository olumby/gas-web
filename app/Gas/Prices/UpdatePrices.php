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
			$storeFile = $this->storagePath . $filename;

			$url = $this->baseUrl . $this->namePrefix . $filename;
			$file = file_get_contents($url);

			$this->filesystem->put($storeFile, $file);

			$zipper = new \ZipArchive();
			$zipper->open($storeFile);

			$zipper->extractTo($this->storagePath);

			$zipper->close();

			$this->filesystem->delete($storeFile);
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

} 