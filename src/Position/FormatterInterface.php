<?php

namespace Slepic\Geo\Position;

use Slepic\Geo\PositionInterface;

/**
 * Formatter defines way to represent position as string using format template provided by the caller.
 */
interface FormatterInterface
{
	/**
	 * @param PositionInterface $position
	 * @param string $format
	 * @return string
	 */
	public function format(PositionInterface $position, string $format): string;
}