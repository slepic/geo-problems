<?php

namespace Slepic\Geo\Tests;

use Slepic\Geo\Position;
use Slepic\Geo\PositionInterface;
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

		$this->assertEquals($to->getLatitude(), $endPoint->getLatitude());
		$this->assertEquals($to->getLongitude(), $endPoint->getLongitude());
	}

	public function provideTwoWayData(): array
	{
		$data = [];
		for ($i = 0; $i < 10; ++$i) {
			$data[] = [
				Position::fromDegrees(rand(-90, 90), rand(-180, 180)),
				Position::fromDegrees(rand(-90, 90), rand(-180, 180)),
			];
		}
		return $data;
	}
}