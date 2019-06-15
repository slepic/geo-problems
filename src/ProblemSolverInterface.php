<?php

namespace Slepic\Geo;

/**
 * Direct and inverse geographical problem solver interface.
 *
 * The shape and size of the geo-structure is implementation specific.
 */
interface ProblemSolverInterface
{
	/**
	 * @param PositionInterface $startPoint
	 * @param MotionInterface $motion
	 * @return PositionInterface
	 */
	public function getDestination(PositionInterface $startPoint, MotionInterface $motion): PositionInterface;

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return MotionInterface
	 */
	public function getMotion(PositionInterface $startPoint, PositionInterface $endPoint): MotionInterface;
}