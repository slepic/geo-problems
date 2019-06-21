<?php

namespace Slepic\Geo;

interface DegreesPositionFactoryInterface
{
	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromDegrees(float $latitude, float $longitude): PositionInterface;
}