<?php

Route::get('/', function() use($app)
{
	$prices = $app->make('prices')->getJson('G95');
	return $prices;
});
