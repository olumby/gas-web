<?php

use Gas\Helpers;
use Gas\Prices\Price;
use Illuminate\Support\Facades\Cache;

class ApiController extends BaseController {

	/**
	 * Price instance.
	 *
	 * @var
	 */
	protected $price;

	/**
	 * @param Price $price
	 */
	function __construct(Price $price)
	{
		$this->price = $price;
	}

	/**
	 * Return all prices for a fuel type.
	 *
	 * @param $format (json|geojson)
	 * @param $name
	 * @return array
	 */
	public function prices($format, $name)
	{
		$query = Cache::remember("$format-$name", 20, function () use ($name)
		{
			return $this->price->ofType($name)->get()->toJson();
		});

		if ($format == 'geojson')
		{
			return Helpers::toGeoJson(json_decode($query, true), ['name', 'hours', 'price']);
		}

		return $query;
	}

	/**
	 * Return prices for a fuel type and geographical region.
	 *
	 * @param $format (json|geojson)
	 * @param $name
	 * @param $lat
	 * @param $lng
	 * @param $prox
	 * @return array
	 */
	public function geoPrices($format, $name, $lat, $lng, $prox)
	{
		$query = Cache::remember("$format-$name-$lat-$lng-$prox", 20, function () use ($name, $lat, $lng, $prox)
		{
			return $this->price->ofType($name)->closeTo($lat, $lng, $prox)->take(10)->get()->toJson();
		});

		if ($format == 'geojson')
		{
			return Helpers::toGeoJson(json_decode($query, true), ['name', 'hours', 'price']);
		}

		return $query;
	}

}