<?php

namespace Slepic\Geo;

interface DegreesToRadiansConverterInterface
{
	/**
	 * @param float $value
	 * @return float
	 */
	public function degreesToRadians(float $value): float;
}