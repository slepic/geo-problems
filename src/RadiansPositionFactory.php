<?php

namespace Slepic\Geo;

class RadiansPositionFactory implements RadiansPositionFactoryInterface
{
	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromRadians(float $latitude, float $longitude): PositionInterface
	{
		return new Position($latitude, $longitude);
	}
}