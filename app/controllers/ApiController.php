<?php

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
		return $this->price->ofType($name)->take(10)->get();
	}

	public function geoPrices($format, $name, $lat, $lng, $prox)
	{
		// Check cache..
		return $this->price->ofType($name)->closeTo($lat, $lng, $prox)->take(10)->get();
	}

}