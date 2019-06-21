<?php

namespace Slepic\Geo;

interface PositionInterface
{
	/**
	 * @return float Latitude in radians
	 */
	public function getLatitudeInRadians(): float;

	/**
	 * @return float Longitude in radians
	 */
	public function getLongitudeInRadians(): float;
}