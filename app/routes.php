<?php

Route::get('/', function() use($app)
{

	$prices = $app->make('prices')->get('GPR');
	return json_decode($prices);
	//$prices = new \Gas\Prices\Prices();
	//return $prices->get('GPR');
});
