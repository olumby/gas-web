<?php

use Gas\Prices\Prices;

class ApiController extends BaseController {

	protected $prices;

	function __construct(Prices $prices)
	{
		$this->prices = $prices;
	}


	public function prices($format, $name)
	{
		return $this->prices->getGeoJson($name);
	}

}