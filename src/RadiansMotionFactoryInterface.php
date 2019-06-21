<?php

namespace Slepic\Geo;

interface RadiansMotionFactoryInterface
{
	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromRadians(float $distance, float $angle): MotionInterface;
}