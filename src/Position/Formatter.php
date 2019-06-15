<?php

namespace Slepic\Geo\Position;

use Slepic\Geo\PositionInterface;

class Formatter implements FormatterInterface
{
	/**
	 * @var string
	 */
	private $defaultFormat;

	/**
	 * @param string $defaultFormat
	 */
	public function __construct(string $defaultFormat = '%D°M\'%S" %c')
	{
		$this->defaultFormat = $defaultFormat;
	}

	/**
	 * Format a position using given format template.
	 *
	 *
	 * Common placeholders:
	 * These can be used in combination with latitude/longitude placeholders to define format of each of the two components separately
	 *
	 * %n - the sign: "-" if negative, empty otherwise
	 * %c - the latitude/longitude cardinality
	 * %r - absolute value in radians
	 * %d - absolute value in degrees
	 * %m - absolute value in minutes of degree
	 * %s - absolute value in seconds of degree
	 * %D - degrees part of the absolute value
	 * %M - minutes part of the absolute value
	 * %S - seconds part of the absolute value
	 * %S.X - where X is a number of decimal places - same as %S but with rounding.
	 *
	 * Latitude/longitude placeholders:
	 * These placeholders stand for each of the two components and can be further customized by providing the component format in braces.
	 *
	 * %a or %a(...) - latitude
	 * %o or %o(...) - longitude
	 *
	 * The form with braces is optional.
	 * The first occurrence of latitude/longitude with braces will define the default format for the respective component for the whole format template
	 * Any occurrences without braces before a braced form is encountered will use the default passed to constructor,
	 * unless a default format for the other component has already been defined during the lookup, in which case it will be used instead.
	 *
	 * Example format A: "%a %a(%c) %a %o %o(%D) %o"
	 *                     1  2      3  4  5      6
	 *
	 * 1 ... latitude uses the constructor default, making it default for upcoming %a's without braces
	 * 2 ... uses its own format "%c"
	 * 3 ... uses the default for this format string which has been determined in step 1
	 * 4 ... longitude uses the default and since latitude already defined a default, it is used instead of the constructor one.
	 * 			This also makes this format default for any upcoming %o's without braces.
	 * 5 ... uses its own format "%D"
	 * 6 ... uses the default for longitude which has been set to the same as for latitude in step 4
	 *
	 * * Example format B: "%a(%c) %a %o(%D) %o"
	 *                       1      2  3      4
	 *
	 * 1 ... latitude uses its own format "%c", making it default for upcoming %a's without braces
	 * 2 ... latitude uses current default set in step 1
	 * 3 ... longitude uses its own format "%D" making it default for upcoming %o's without braces
	 * 4 ... longitude uses the default longitude format set in step 3
	 *
	 *
	 * @param PositionInterface $position
	 * @param string $format
	 * @return string
	 */
	public function format(PositionInterface $position, string $format): string
	{
		$latitudeFormat = null;
		$longitudeFormat = null;
		$output = \preg_replace_callback(
			'/%(a|o)(\(([^()]+)\))?/',
			function ($matches) use ($position, &$latitudeFormat, &$longitudeFormat) {
				$isLatitude = $matches[1] === 'a';
				$ownFormat = isset($matches[3]) ? $matches[3] : null;
				if ($isLatitude) {
					if ($latitudeFormat === null) {
						$latitudeFormat = $ownFormat ?? $longitudeFormat ?? $this->defaultFormat;
					}
					return $this->formatLatitude($position->getLatitude(), $ownFormat ?? $latitudeFormat);
				} else {
					if ($longitudeFormat === null) {
						$longitudeFormat = $ownFormat ?? $latitudeFormat ?? $this->defaultFormat;
					}
					return $this->formatLongitude($position->getLongitude(), $ownFormat ?? $longitudeFormat);
				}
			},
			$format
		);
		if ($latitudeFormat === null && $longitudeFormat === null) {
			throw new \InvalidArgumentException('The format does not contain any placeholders.');
		}
		return $output;
	}

	private function formatLatitude(float $latitude, string $format): string
	{
		return $this->formatCoordinate($latitude, $format, 'N', 'S');
	}

	private function formatLongitude(float $longitude, string $format): string
	{
		return $this->formatCoordinate($longitude, $format, 'E', 'W');
	}

	private function formatCoordinate(float $value, string $format, string $positiveCardinality, string $negativeCardinality): string
	{
		$info = [
			'n' => $value >= 0.0 ? '' : '-',
			'c' => $value >= 0.0 ? $positiveCardinality : $negativeCardinality,
			'r' => abs($value),
		];
		$info['d'] = $info['r'] * 180.0 / M_PI;
		$info['D'] = floor($info['d']);
		$info['m'] = $info['d'] * 60.0;
		$info['M'] = floor(($info['d'] - $info['D']) * 60.0);
		$info['s'] = $info['m'] * 60.0;
		$info['S(\.(\d))?'] = ($info['m'] - floor($info['m'])) * 60.0;
		return \preg_replace_callback(
			'/%(' .\implode('|', \array_keys($info)) . ')/',
			function ($matches) use ($info, $positiveCardinality, $negativeCardinality) {
				if ($matches[1][0] === 'S') {
					if (isset($matches[3])) {
						$precision = (int) $matches[3];
						return round($info['S(\.(\d))?'], $precision);
					}
					return $info['S(\.(\d))?'];
				}
				return $info[$matches[1]];
			},
			$format
		);
	}
}