<?php

namespace Slepic\Geo;

/**
 * An ultimate DI connector for the library.
 *
 * This is just a blob of all the functionality of this lib.
 * Mostly good to see what this lib has to offer.
 * But you better use the individual services and have your DI container instantiate them.
 */
class Sphere implements
	DegreesToRadiansConverterInterface,
	RadiansToDegreesConverterInterface,
	RadiansPositionFactoryInterface,
	RadiansMotionFactoryInterface,
	DegreesPositionFactoryInterface,
	DegreesMotionFactoryInterface,
	DirectProblemInterface,
	InverseProblemInterface,
	MidpointProblemInterface
{
	const EARTH_RADIUS = 6371210.0;

	/**
	 * @var DirectProblemInterface
	 */
	private $directProblem;

	/**
	 * @var InverseProblemInterface
	 */
	private $inverseProblem;

	/**
	 * @var MidpointProblemInterface
	 */
	private $midpointProblem;

	/**
	 * @var DegreesPositionFactoryInterface
	 */
	private $degreesPositionFactory;

	/**
	 * @var DegreesMotionFactoryInterface
	 */
	private $degreesMotionFactory;

	/**
	 * @var RadiansPositionFactoryInterface
	 */
	private $radiansPositionFactory;

	/**
	 * @var RadiansMotionFactoryInterface
	 */
	private $radiansMotionFactory;

	/**
	 * @var DegreesToRadiansConverterInterface
	 */
	private $degreeConverter;

	/**
	 * @var RadiansToDegreesConverterInterface
	 */
	private $radiansConverter;

	/**
	 * @var float
	 */
	private $radius;

	/**
	 * @param float|null $radius Radius of the sphere. Defaults to radius of the Earth.
	 * @param RadiansPositionFactoryInterface|null $positionFactory
	 * @param RadiansMotionFactoryInterface|null $motionFactory
	 * @param DegreesToRadiansConverterInterface|null $degreeConverter
	 * @param RadiansToDegreesConverterInterface|null $radiansConverter
	 */
	public function __construct(
		?float $radius = null,
		RadiansPositionFactoryInterface $positionFactory = null,
		RadiansMotionFactoryInterface $motionFactory = null,
		DegreesToRadiansConverterInterface $degreeConverter = null,
		RadiansToDegreesConverterInterface $radiansConverter = null
	) {
		if ($radius === null) {
			$this->radius = self::EARTH_RADIUS;
		} elseif ($radius > 0.0) {
			$this->radius = $radius;
		} else {
			throw new \InvalidArgumentException('Radius must be positive.');
		}
		$this->degreeConverter = $degreeConverter ?? new AngleUnitConverter();
		$this->radiansConverter = $radiansConverter
			?? ($this->degreeConverter instanceof RadiansToDegreesConverterInterface ? $this->degreeConverter : null)
			?? new AngleUnitConverter();
		$this->radiansPositionFactory = $positionFactory ?? new RadiansPositionFactory();
		$this->radiansMotionFactory = $motionFactory ?? new RadiansMotionFactory();

		$this->degreesPositionFactory = new DegreesPositionFactory($this->radiansPositionFactory, $this->degreeConverter);
		$this->degreesMotionFactory = new DegreesMotionFactory($this->radiansMotionFactory, $this->degreeConverter);

		$this->directProblem = new DirectProblem($this->radius, $this->radiansPositionFactory);
		$this->inverseProblem = new InverseProblem($this->radius, $this->radiansMotionFactory);
		$this->midpointProblem = new MidpointProblem($this->radiansPositionFactory);
	}

	/**
	 * @return float
	 */
	public function getRadius(): float
	{
		return $this->radius;
	}

	/**
	 * @return DegreesToRadiansConverterInterface
	 */
	public function getDegreesToRadiansConverter(): DegreesToRadiansConverterInterface
	{
		return $this->degreeConverter;
	}

	/**
	 * @return RadiansToDegreesConverterInterface
	 */
	public function getRadiansToDegreesConverter(): RadiansToDegreesConverterInterface
	{
		return $this->radiansConverter;
	}

	/**
	 * @return RadiansPositionFactoryInterface
	 */
	public function getRadiansPositionFactory(): RadiansPositionFactoryInterface
	{
		return $this->radiansPositionFactory;
	}

	/**
	 * @return RadiansMotionFactoryInterface
	 */
	public function getRadiansMotionFactory(): RadiansMotionFactoryInterface
	{
		return $this->radiansMotionFactory;
	}

	/**
	 * @return DegreesPositionFactoryInterface
	 */
	public function getDegreesPositionFactory(): DegreesPositionFactoryInterface
	{
		return $this->degreesPositionFactory;
	}

	/**
	 * @return DegreesMotionFactoryInterface
	 */
	public function getDegreesMotionFactory(): DegreesMotionFactoryInterface
	{
		return $this->degreesMotionFactory;
	}

	/**
	 * @return DirectProblemInterface
	 */
	public function getDirectProblem(): DirectProblemInterface
	{
		return $this->directProblem;
	}

	/**
	 * @return InverseProblemInterface
	 */
	public function getInverseProblem(): InverseProblemInterface
	{
		return $this->inverseProblem;
	}

	/**
	 * @return MidpointProblemInterface
	 */
	public function getMidpointProblem(): MidpointProblemInterface
	{
		return $this->midpointProblem;
	}

	/**
	 * @param float $angle
	 * @return float
	 */
	public function degreesToRadians(float $angle): float
	{
		return $this->degreeConverter->degreesToRadians($angle);
	}

	/**
	 * @param float $angle
	 * @return float
	 */
	public function radiansToDegrees(float $angle): float
	{
		return $this->radiansConverter->radiansToDegrees($angle);
	}

	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromRadians(float $latitude, float $longitude): PositionInterface
	{
		return $this->radiansPositionFactory->positionFromRadians($latitude, $longitude);
	}

	/**
	 * @param float $latitude
	 * @param float $longitude
	 * @return PositionInterface
	 */
	public function positionFromDegrees(float $latitude, float $longitude): PositionInterface
	{
		return $this->degreesPositionFactory->positionFromDegrees($latitude, $longitude);
	}

	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromRadians(float $distance, float $angle): MotionInterface
	{
		return $this->radiansMotionFactory->motionFromRadians($distance, $angle);
	}

	/**
	 * @param float $distance
	 * @param float $angle
	 * @return MotionInterface
	 */
	public function motionFromDegrees(float $distance, float $angle): MotionInterface
	{
		return $this->degreesMotionFactory->motionFromDegrees($distance, $angle);
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param MotionInterface $motion
	 * @return PositionInterface
	 */
	public function getDestination(PositionInterface $startPoint, MotionInterface $motion): PositionInterface
	{
		return $this->directProblem->getDestination($startPoint, $motion);
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return MotionInterface
	 */
	public function getMotion(PositionInterface $startPoint, PositionInterface $endPoint): MotionInterface
	{
		return $this->inverseProblem->getMotion($startPoint, $endPoint);
	}

	/**
	 * @param PositionInterface $startPoint
	 * @param PositionInterface $endPoint
	 * @return PositionInterface
	 */
	public function getMidpoint(PositionInterface $startPoint, PositionInterface $endPoint): PositionInterface
	{
		return $this->midpointProblem->getMidpoint($startPoint, $endPoint);
	}
}