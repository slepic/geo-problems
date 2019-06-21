<?php

namespace Slepic\Geo;

interface MidpointProblemInterface
{
	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return PositionInterface
	 */
	public function getMidpoint(PositionInterface $startPoint, PositionInterface $endPoint): PositionInterface;
}