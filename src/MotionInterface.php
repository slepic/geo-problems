<?php

namespace Slepic\Geo;

interface MotionInterface
{
	/**
	 * @return float Distance in reference units
	 */
	public function getDistance(): float;

	/**
	 * @return float Bearing angle in radians
	 */
	public function getAngle(): float;
}