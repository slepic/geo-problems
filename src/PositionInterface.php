<?php

namespace Slepic\Geo;

interface PositionInterface
{
	/**
	 * @return float Latitude in radians
	 */
	public function getLatitude(): float;

	/**
	 * @return float Longitude in radians
	 */
	public function getLongitude(): float;
}