<?php namespace Gas;

class Helpers {

	static function toGeoJson($array, $properties)
	{
		$geoJson = [
			'type'     => 'FeatureCollection',
			'features' => []
		];

		foreach ($array as $element)
		{
			$newPart = [
				'geometry' => [
					'type' => 'Point',
					'coordinates' => [
						(float)$element['lng'],
						(float)$element['lat']
					]
				],
				'type' => 'Feature'
			];

			foreach ($properties as $property)
			{
				$newPart['properties'][$property] = $element[$property];
			}

			$geoJson['features'][] = $newPart;
		}

		return $geoJson;
	}

} 