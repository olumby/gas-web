<?php namespace Gas\Prices;

use Illuminate\Support\Facades\DB;

class Price extends \Eloquent {

	/**
	 * Entity uses eloquent timestamps.
	 *
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'prices';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * Returns prices based on the fuel type.
	 *
	 * @param $query
	 * @param $type
	 * @return mixed
	 */
	public function scopeOfType($query, $type)
	{
		return $query->whereType($type);
	}

	/**
	 * Returns prices based on coordinates.
	 *
	 * @param $query
	 * @param $type
	 * @param $lat
	 * @param $lng
	 * @return mixed
	 */
	public function scopeCloseTo($query, $type, $lat, $lng)
	{
		if (!is_numeric($lat) || !is_numeric($lng))
			dd("error");

		$magic = "( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) as distance";

		$query->select('*', DB::raw($magic))
			->whereType($type)
			->having('distance', '<', 10)
			->orderBy('distance', 'asc');

		return $query;
	}

}
