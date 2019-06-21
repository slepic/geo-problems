<?php

namespace Slepic\Geo;

class InverseProblem implements InverseProblemInterface
{
	/**
	 * @var float
	 */
	private $radius;

	/**
	 * @var RadiansMotionFactoryInterface
	 */
	private $motionFactory;

	/**
	 * @param float $radius
	 * @param RadiansMotionFactoryInterface $motionFactory
	 */
	public function __construct(float $radius, RadiansMotionFactoryInterface $motionFactory)
	{
		$this->radius = $radius;
		$this->motionFactory = $motionFactory;
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return MotionInterface
	 */
	public function getMotion(PositionInterface $startPoint, PositionInterface $endPoint): MotionInterface
	{
		$startLatitude = $startPoint->getLatitudeInRadians();
		$startLongitude = $startPoint->getLongitudeInRadians();
		$endLatitude = $endPoint->getLatitudeInRadians();
		$endLongitude = $endPoint->getLongitudeInRadians();
		$startLatitudeCosine = cos($startLatitude);
		$endLatitudeCosine = cos($endLatitude);
		$startLatitudeSine = sin($startLatitude);
		$endLatitudeSine = sin($endLatitude);
		$longitudeDelta = $endLongitude - $startLongitude;
		$longitudeDeltaSine = sin($longitudeDelta);
		$longitudeDeltaCosine = cos($longitudeDelta);
		$endTimesDeltaCosine = $endLatitudeCosine * $longitudeDeltaCosine;
		$term1 = $endLatitudeCosine * $longitudeDeltaSine;
		$term2 = $startLatitudeCosine * $endLatitudeSine - $startLatitudeSine * $endTimesDeltaCosine;
		$y = sqrt($term1 * $term1 + $term2 * $term2);
		$x = $startLatitudeSine * $endLatitudeSine + $startLatitudeCosine * $endTimesDeltaCosine;
		$ad = atan2($y, $x);
		$distance = $this->radius * $ad;
		$bearing = atan2($term1, $term2);
		$bearing = $bearing >= 0 ? $bearing : ($bearing + 2 * M_PI);
		return $this->motionFactory->motionFromRadians($distance, $bearing);
	}
}