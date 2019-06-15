<?php

namespace Slepic\Geo\Tests;

use PHPUnit\Framework\TestCase;
use Slepic\Geo\Position;
use Slepic\Geo\Position\Formatter;
use Slepic\Geo\PositionInterface;

class FormatterTest extends TestCase
{
	/**
	 * @param PositionInterface $position
	 * @param string $format
	 * @param string $expectedOutput
	 * @dataProvider provideSamples
	 */
	public function testSample(PositionInterface $position, string $format, string $expectedOutput): void
	{
		$formatter = new Formatter();
		$output = $formatter->format($position, $format);
		$this->assertSame($expectedOutput, $output);
	}

	public function provideSamples(): array
	{
		return [
			[
				new Position(0.0, deg2rad(0.0)),
				'%a(%D°%M\'%S" %c), %o',
				'0°0\'0" N, 0°0\'0" E'
			],
			[
				new Position(deg2rad(-1 * (45 + 15/60 + 25.15 / 3600)), M_PI / 180.0 * (30 + (13 / 60.0) + (40.6 / 3600.0))),
				'%a(%D°%M\'%S.4" %c), %o(%D°%M\'%S.0" %c)',
				'45°15\'25.15" S, 30°13\'41" E'
			],
		];
	}
}