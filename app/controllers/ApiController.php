<?php

use Gas\Helpers;
use Gas\Prices\Price;
use Illuminate\Support\Facades\Cache;

class ApiController extends BaseController {

	protected $prices;

	function __construct(Price $price)
	{
		$this->price = $price;
	}
	
	public function prices($format, $name)
	{
		$query = Cache::remember("$format-$name", 20, function() use($name)
		{
			return $this->price->ofType($name)->get()->toJson();
		});

		if ($format == 'geojson')
		{
			return Helpers::toGeoJson(json_decode($query, true), ['name', 'hours', 'price']);
		}

		return $query;
	}

	public function geoPrices($format, $name, $lat, $lng, $prox)
	{
		$query = Cache::remember("$format-$name-$lat-$lng-$prox", 20, function() use($name, $lat, $lng, $prox)
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