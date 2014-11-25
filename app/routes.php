<?php

Route::get('/', function() use($app)
{
	return $app->make('prices')->get('GPR');
	//$prices = new \Gas\Prices\Prices();
	//return $prices->get('GPR');
});
