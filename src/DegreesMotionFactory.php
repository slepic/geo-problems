<?php

namespace Slepic\Geo;

class DegreesMotionFactory implements DegreesMotionFactoryInterface
{
	/**
	 * @var RadiansMotionFactoryInterface
	 */
	private $factory;

	/**
	 * @var DegreesToRadiansConverterInterface
	 */
	private $converter;

	/**
	 * @param RadiansMotionFactoryInterface $factory
	 * @param DegreesToRadiansConverterInterface $converter
	 */
	public function __construct(RadiansMotionFactoryInterface $factory, DegreesToRadiansConverterInterface $converter)
	{
		$this->factory = $factory;
		$this->converter = $converter;
	}

	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromDegrees(float $distance, float $angle): MotionInterface
	{
		return $this->factory->motionFromRadians(
			$distance,
			$this->converter->degreesToRadians($angle)
		);
	}
}