<?php

namespace Slepic\Geo;

interface RadiansPositionFactoryInterface
{
	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromRadians(float $latitude, float $longitude): PositionInterface;
}