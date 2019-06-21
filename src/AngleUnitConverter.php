<?php

namespace Slepic\Geo;

final class AngleUnitConverter implements DegreesToRadiansConverterInterface, RadiansToDegreesConverterInterface
{
	/**
	 * @var float
	 */
	private $ratio = M_PI / 180.0;

	/**
	 * @param float $value
	 * @return float
	 */
	public function degreesToRadians(float $value): float
	{
		return $value * $this->ratio;
	}

	/**
	 * @param float $value
	 * @return float
	 */
	public function radiansToDegrees(float $value): float
	{
		return $value / $this->ratio;
	}
}