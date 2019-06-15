<?php

namespace Slepic\Geo\Position;

use Slepic\Geo\PositionInterface;

/**
 * Binds a formatter to a default value and then provides both FormatInterface and FormatterInterface
 * When used as formatter it just delegates to inner formatter.
 * When used as format, it delegates the default format to the formatter.
 */
class Format implements FormatInterface, FormatterInterface
{
	/**
	 * @var FormatterInterface
	 */
	private $formatter;

	/**
	 * @var string
	 */
	private $defaultFormat;

	/**
	 * @param FormatterInterface $formatter
	 * @param string $defaultFormat
	 */
	public function __construct(FormatterInterface $formatter = null, string $defaultFormat = '%a(%D°%M\'%S" %c), %o')
	{
		$this->formatter = $formatter ?? new Formatter();
		$this->defaultFormat = $defaultFormat;
	}

	/**
	 * @param PositionInterface $position
	 * @param string|null $format
	 * @return string
	 */
	public function format(PositionInterface $position, string $format = null): string
	{
		return $this->formatter->format($position, $format ?? $this->defaultFormat);
	}
}