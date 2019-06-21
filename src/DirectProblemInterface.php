<?php

namespace Slepic\Geo;

interface DirectProblemInterface
{
	/**
	 * @param PositionInterface $startPoint
	 * @param MotionInterface $motion
	 * @return PositionInterface
	 */
	public function getDestination(PositionInterface $startPoint, MotionInterface $motion): PositionInterface;
}