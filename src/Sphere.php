<?php

namespace Slepic\Geo;

/**
 * This class solves the problems, pretending the geo-structure is an ideal sphere.
 */
class Sphere implements ProblemSolverInterface
{
	/**
	 * @var float
	 */
	private $radius;

	/**
	 * @param float|null $radius Radius of the sphere. Defaults to radius of the Earth.
	 */
	public function __construct(?float $radius = null)
	{
		if ($radius === null) {
			$this->radius = 6371210.0;
		} elseif ($radius <= 0.0 || !is_finite($radius)) {
			throw new \InvalidArgumentException('Radius must be positive.');
		} else {
			$this->radius = $radius;
		}
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param MotionInterface $motion
	 * @return PositionInterface
	 */
	public function getDestination(PositionInterface $startPoint, MotionInterface $motion): PositionInterface
	{
		$startLatitude    = $startPoint->getLatitude();
		$startLongitude   = $startPoint->getLongitude();
		$startLatitudeCosine = cos($startLatitude);
		$startLatitudeSine = sin($startLatitude);
		$dr = $motion->getDistance() / $this->radius;
		$bearing = $motion->getAngle();
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
		return new Position($endLatitude, $endLongitude);
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return MotionInterface
	 */
	public function getMotion(PositionInterface $startPoint, PositionInterface $endPoint): MotionInterface
	{
		$startLatitude = $startPoint->getLatitude();
		$startLongitude = $startPoint->getLongitude();
		$endLatitude = $endPoint->getLatitude();
		$endLongitude = $endPoint->getLongitude();
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
		return new Motion($distance, $bearing);
	}
}