<?php

class PageController extends BaseController {

	public function index()
	{
		return View::make('hello');
	}

	public function prices()
	{
		return View::make('prices');
	}

}
