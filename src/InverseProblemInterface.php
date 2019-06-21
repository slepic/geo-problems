<?php

namespace Slepic\Geo;

interface InverseProblemInterface
{
	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return MotionInterface
	 */
	public function getMotion(PositionInterface $startPoint, PositionInterface $endPoint): MotionInterface;
}