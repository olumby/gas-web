<?php

use Gas\Prices\Price;

class PageController extends BaseController {

	public function index()
	{
		return Price::closeTo('GPR', 39.467743, -0.359759)->take(10)->get();
	}

	public function prices()
	{
		return View::make('prices');
	}

}
