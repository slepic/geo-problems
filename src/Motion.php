<?php

namespace Slepic\Geo;

class Motion implements MotionInterface
{
	/**
	 * @var float
	 */
	private $distance;

	/**
	 * @var float
	 */
	private $angle;

	/**
	 * @param float $distance
	 * @param float $angle
	 */
	public function __construct(float $distance, float $angle)
	{
		if ($distance < 0) {
			throw new \InvalidArgumentException('Distance must be non-negative.');
		}
		if ($angle < 0 || ($angle - 0.000000000000001) >= 2 * M_PI) {
			throw new \InvalidArgumentException('Angle must be in interval <0, 2*pi).');
		}
		$this->distance = $distance;
		$this->angle = $angle;
	}

	/**
	 * @return float Distance in reference units
	 */
	public function getDistance(): float
	{
		return $this->distance;
	}

	/**
	 * @return float Bearing angle in radians
	 */
	public function getAngleInRadians(): float
	{
		return $this->angle;
	}
}