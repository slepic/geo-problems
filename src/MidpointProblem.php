<?php

namespace Slepic\Geo;

class MidpointProblem implements MidpointProblemInterface
{
	/**
	 * @var RadiansPositionFactoryInterface
	 */
	private $positionFactory;

	/**
	 * @param RadiansPositionFactoryInterface $positionFactory
	 */
	public function __construct(RadiansPositionFactoryInterface $positionFactory)
	{
		$this->positionFactory = $positionFactory;
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return PositionInterface
	 */
	public function getMidpoint(PositionInterface $startPoint, PositionInterface $endPoint): PositionInterface
	{
		$startLatitude = $startPoint->getLatitudeInRadians();
		$startLongitude = $startPoint->getLongitudeInRadians();
		$endLatitude = $endPoint->getLatitudeInRadians();
		$endLongitude = $endPoint->getLongitudeInRadians();
		$longitudeDelta = $endLongitude - $startLongitude;
		$Bx = cos($endLatitude) * cos($longitudeDelta);
		$By = cos($endLatitude) * sin($longitudeDelta);
		$term = cos($startLatitude) + $Bx;
		$latitude = atan2(sin($startLatitude) + sin($endLatitude), sqrt($term * $term + $By * $By));
		$longitude = $startLongitude + atan2($By, $term);
		while ($longitude > M_PI) {
			$longitude -= 2 * M_PI;
		}
		while ($longitude < -M_PI) {
			$longitude += 2 * M_PI;
		}
		return $this->positionFactory->positionFromRadians($latitude, $longitude);
	}
}