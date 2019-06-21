<?php

namespace Slepic\Geo;

class DirectProblem implements DirectProblemInterface
{
	/**
	 * @var float
	 */
	private $radius;

	/**
	 * @var RadiansPositionFactoryInterface
	 */
	private $positionFactory;

	/**
	 * @param float $radius
	 * @param RadiansPositionFactoryInterface $positionFactory
	 */
	public function __construct(float $radius, RadiansPositionFactoryInterface $positionFactory)
	{
		$this->radius = $radius;
		$this->positionFactory = $positionFactory;
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param MotionInterface $motion
	 * @return PositionInterface
	 */
	public function getDestination(PositionInterface $startPoint, MotionInterface $motion): PositionInterface
	{
		$startLatitude    = $startPoint->getLatitudeInRadians();
		$startLongitude   = $startPoint->getLongitudeInRadians();
		$startLatitudeCosine = cos($startLatitude);
		$startLatitudeSine = sin($startLatitude);
		$dr = $motion->getDistance() / $this->radius;
		$bearing = $motion->getAngleInRadians();
		$sinDr = sin($dr);
		$cosDr = cos($dr);
		$endLatitude = asin($startLatitudeSine * $cosDr + $startLatitudeCosine * $sinDr * cos($bearing));
		$endLongitude = $startLongitude + atan2(
				sin($bearing) * $sinDr * $startLatitudeCosine,
				$cosDr - $startLatitudeSine * sin($endLatitude)
			);
		while ($endLongitude > M_PI) {
			$endLongitude -= 2 * M_PI;
		}
		while ($endLongitude < -M_PI) {
			$endLongitude += 2 * M_PI;
		}
		return $this->positionFactory->positionFromRadians($endLatitude, $endLongitude);
	}
}