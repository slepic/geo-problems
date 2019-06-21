<?php

namespace Slepic\Geo;

class Position implements PositionInterface
{
	/**
	 * @var float
	 */
	private $latitude;

	/**
	 * @var float
	 */
	private $longitude;

	/**
	 * @param float $latitude
	 * @param float $longitude
	 */
	public function __construct(float $latitude, float $longitude)
	{
		if (\abs($latitude) > M_PI_2) {
			throw new \InvalidArgumentException(sprintf('Latitude must be in interval <-pi/2, pi/2>, %f given.', $latitude));
		}
		if (\abs($longitude) > M_PI) {
			throw new \InvalidArgumentException(sprintf('Longitude must be in interval <-pi, pi>, %f given..', $longitude));
		}
		$this->latitude = $latitude;
		$this->longitude = $longitude;
	}

	/**
	 * @return float Latitude in radians
	 */
	public function getLatitudeInRadians(): float
	{
		return $this->latitude;
	}

	/**
	 * @return float Longitude in radians
	 */
	public function getLongitudeInRadians(): float
	{
		return $this->longitude;
	}
}