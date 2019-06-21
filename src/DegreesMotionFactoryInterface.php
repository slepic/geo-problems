<?php

namespace Slepic\Geo;

interface DegreesMotionFactoryInterface
{
	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromDegrees(float $distance, float $angle): MotionInterface;
}