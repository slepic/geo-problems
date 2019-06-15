<?php

namespace Slepic\Geo\Position;

use Slepic\Geo\PositionInterface;

/**
 * Format defines way to represent position as string by its own means.
 */
interface FormatInterface
{
	/**
	 * @param PositionInterface $position
	 * @return string
	 */
	public function format(PositionInterface $position): string;
}