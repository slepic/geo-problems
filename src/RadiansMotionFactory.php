<?php

namespace Slepic\Geo;

class RadiansMotionFactory implements RadiansMotionFactoryInterface
{
	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromRadians(float $distance, float $angle): MotionInterface
	{
		return new Motion($distance, $angle);
	}
}