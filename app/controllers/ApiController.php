<?php

use Gas\Helpers;
use Gas\Prices\Price;

class ApiController extends BaseController {

	protected $prices;

	function __construct(Price $price)
	{
		$this->price = $price;
	}


	public function prices($format, $name)
	{
		// Check cache..
		$query = $this->price->ofType($name)->get();

		if ($format == 'geojson')
		{
			return Helpers::toGeoJson($query->toArray(), ['name', 'hours', 'price']);
		}

		return $query;
	}

	public function geoPrices($format, $name, $lat, $lng, $prox)
	{
		// Check cache..
		$query = $this->price->ofType($name)->closeTo($lat, $lng, $prox)->take(10)->get();

		if ($format == 'geojson')
		{
			return Helpers::toGeoJson($query->toArray(), ['name', 'hours', 'price']);
		}

		return $query;
	}

}