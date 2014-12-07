<?php

Route::get('/', [
	'uses' => 'PageController@index',
	'as'   => 'home'
]);

Route::get('precios', [
	'uses' => 'PageController@prices',
	'as'   => 'es.prices'
]);