<?php

namespace Slepic\Geo\Tests;

use Slepic\Geo\AngleUnitConverter;
use Slepic\Geo\DegreesPositionFactory;
use Slepic\Geo\PositionInterface;
use Slepic\Geo\RadiansPositionFactory;
use Slepic\Geo\Sphere;
use PHPUnit\Framework\TestCase;

class SphereTest extends TestCase
{
	/**
	 * @param PositionInterface $from
	 * @param PositionInterface $to
	 * @dataProvider provideTwoWayData
	 */
	public function testTwoWay(PositionInterface $from, PositionInterface $to)
	{
		$sphere = new Sphere();
		$motion = $sphere->getMotion($from, $to);
		$endPoint = $sphere->getDestination($from, $motion);

		$this->assertEquals($to->getLatitudeInRadians(), $endPoint->getLatitudeInRadians());
		$this->assertEquals($to->getLongitudeInRadians(), $endPoint->getLongitudeInRadians());
	}

	public function provideTwoWayData(): array
	{
		$factory = new DegreesPositionFactory(new RadiansPositionFactory(), new AngleUnitConverter());
		$data = [];
		for ($i = 0; $i < 10; ++$i) {
			$data[] = [
				$factory->positionFromDegrees(rand(-90, 90), rand(-180, 180)),
				$factory->positionFromDegrees(rand(-90, 90), rand(-180, 180)),
			];
		}
		return $data;
	}
}