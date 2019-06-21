<?php

namespace Slepic\Geo;

interface RadiansToDegreesConverterInterface
{
	/**
	 * @param float $value
	 * @return float
	 */
	public function radiansToDegrees(float $value): float;
}