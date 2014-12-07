<?php

Route::get('/', [
	'uses' => 'PageController@index',
	'as'   => 'home'
]);

Route::get('precios', [
	'uses' => 'PageController@prices',
	'as'   => 'es.prices'
]);

Route::get('api/prices/{format}/{name}', [
	'uses' => 'ApiController@prices',
	'as'   => 'api.prices'
]);

Route::get('api/prices/{format}/{name}/{lat},{lng},{prox}', [
	'uses' => 'ApiController@geoPrices',
	'as'   => 'api.prices.geo'
]);