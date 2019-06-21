<?php

namespace Slepic\Geo;

class DegreesPositionFactory implements DegreesPositionFactoryInterface
{
	/**
	 * @var RadiansPositionFactoryInterface
	 */
	private $factory;

	/**
	 * @var DegreesToRadiansConverterInterface
	 */
	private $converter;

	/**
	 * @param RadiansPositionFactoryInterface $factory
	 * @param DegreesToRadiansConverterInterface $converter
	 */
	public function __construct(RadiansPositionFactoryInterface $factory, DegreesToRadiansConverterInterface $converter)
	{
		$this->factory = $factory;
		$this->converter = $converter;
	}

	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromDegrees(float $latitude, float $longitude): PositionInterface
	{
		return $this->factory->positionFromRadians(
			$this->converter->degreesToRadians($latitude),
			$this->converter->degreesToRadians($longitude)
		);
	}
}