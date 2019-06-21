<?php

require_once dirname(__DIR__) .'/vendor/autoload.php';

$max = 1000000;
$maxDistance = M_PI * \TeamA\Geo\Point::EARTH_RADIUS;
$rad2deg = 180.0 / M_PI;
$deg2rad = M_PI / 180.0;

echo "Direct problem:\n";
echo "Comparing TeamA vs. Slepic implementation with $max random samples.\n";

$start = \microtime(true);
for ($i = 0; $i < $max; ++$i) {
	$p1 = \TeamA\Geo\Point::create(\rand(-180, 180), \rand(-90, 90));
	$p2 = \TeamA\Geo\Point::createByBearing($p1, \rand(0, 360), \rand(1, $maxDistance));
	$latitude = $p2->getLatitude();
	$longitude = $p2->getLongitude();
	//$latitude = $p2->getLatitude() * $deg2rad;
	//$longitude = $p2->getLongitude() * $deg2rad;
}
$end = \microtime(true);
$teamDelta = $end - $start;
echo "TeamA: " . $teamDelta . \PHP_EOL;

$start = \microtime(true);
$sphere = new \Slepic\Geo\Sphere();
$dpf = $sphere->getDegreesPositionFactory();
$dmf = $sphere->getDegreesMotionFactory();
$problem = $sphere->getDirectProblem();
for ($i = 0; $i < $max; ++$i) {
	$p1 = $dpf->positionFromDegrees(\rand(-90, 90), \rand(-180, 180));
	$m = $dmf->motionFromDegrees(\rand(1, $maxDistance), \rand(0, 360));
	$p2 = $problem->getDestination($p1, $m);
	$latitude = $p2->getLatitudeInRadians();
	$longitude = $p2->getLongitudeInRadians();
}
$end = \microtime(true);
$slepicDelta = $end - $start;
echo "Slepic: " . $slepicDelta . \PHP_EOL;

echo "Slepic / TeamA: " . \round($slepicDelta / $teamDelta * 100, 2) . "%\n";
echo "Slepic is " . \round(100 - $slepicDelta / $teamDelta * 100, 2) . "% faster then TeamA\n";
echo "TeamA / Slepic: " . \round($teamDelta / $slepicDelta * 100, 2) . "%\n";
echo "TeamA is " . \round($teamDelta / $slepicDelta * 100 - 100, 2) . "% slower then Slepic\n";

echo \PHP_EOL;
echo "Inverse problem:\n";
echo "Comparing TeamA vs. Slepic implementation with $max random samples.\n";

$start = \microtime(true);
for ($i = 0; $i < $max; ++$i) {
	$p1 = \TeamA\Geo\Point::create(\rand(-180, 180), \rand(-90, 90));
	$p2 = \TeamA\Geo\Point::create(\rand(-180, 180), \rand(-90, 90));
	$line = new \TeamA\Geo\Line($p1, $p2);
	$distance = $line->getDistance();
	$bearing = $line->getInitialBearing();
	//$bearing = $line->getInitialBearing() * $deg2rad;
}
$end = \microtime(true);
$teamDelta = $end - $start;
echo "TeamA: " . $teamDelta . \PHP_EOL;

$start = \microtime(true);
$sphere = new \Slepic\Geo\Sphere();
$dpf = $sphere->getDegreesPositionFactory();
$problem = $sphere->getInverseProblem();
for ($i = 0; $i < $max; ++$i) {
	$p1 = $dpf->positionFromDegrees(\rand(-90, 90), \rand(-180, 180));
	$p2 = $dpf->positionFromDegrees(\rand(-90, 90), \rand(-180, 180));
	$motion = $problem->getMotion($p1, $p2);
	$distance = $motion->getDistance();
	$bearing = $motion->getAngleInRadians() * $rad2deg;
}
$end = \microtime(true);
$slepicDelta = $end - $start;
echo "Slepic: " . $slepicDelta . \PHP_EOL;

echo "Slepic / TeamA: " . \round($slepicDelta / $teamDelta * 100, 2) . "%\n";
echo "Slepic is " . \round(100 - $slepicDelta / $teamDelta * 100, 2) . "% faster then TeamA\n";
echo "TeamA / Slepic: " . \round($teamDelta / $slepicDelta * 100, 2) . "%\n";
echo "TeamA is " . \round($teamDelta / $slepicDelta * 100 - 100, 2) . "% slower then Slepic\n";

echo \PHP_EOL;
echo "Midpoint problem:\n";
echo "Comparing TeamA vs. Slepic implementation with $max random samples.\n";
/*
$start = \microtime(true);
for ($i = 0; $i < $max; ++$i) {
	$p1 = \TeamA\Geo\Point::create(\rand(-180, 180), \rand(-90, 90));
	$p2 = \TeamA\Geo\Point::create(\rand(-180, 180), \rand(-90, 90));
	$line = new \TeamA\Geo\Line($p1, $p2);
	$mp = $line->getMidPoint();
	$latitude = $mp->getLatitude();
	$longitude = $mp->getLongitude();
}
$end = \microtime(true);
$teamDelta = $end - $start;
echo "TeamA: " . $teamDelta . \PHP_EOL;
*/
$start = \microtime(true);
$sphere = new \Slepic\Geo\Sphere();
$dpf = $sphere->getDegreesPositionFactory();
$problem = $sphere->getMidpointProblem();
for ($i = 0; $i < $max; ++$i) {
	$p1 = $dpf->positionFromDegrees(\rand(-90, 90), \rand(-180, 180));
	$p2 = $dpf->positionFromDegrees(\rand(-90, 90), \rand(-180, 180));
	$mp = $problem->getMidpoint($p1, $p2);
	$latitude = $mp->getLatitudeInRadians();
	$longitude = $mp->getLongitudeInRadians();
}
$end = \microtime(true);
$slepicDelta = $end - $start;
echo "Slepic: " . $slepicDelta . \PHP_EOL;

echo "Slepic / TeamA: " . \round($slepicDelta / $teamDelta * 100, 2) . "%\n";
echo "Slepic is " . \round(100 - $slepicDelta / $teamDelta * 100, 2) . "% faster then TeamA\n";
echo "TeamA / Slepic: " . \round($teamDelta / $slepicDelta * 100, 2) . "%\n";
echo "TeamA is " . \round($teamDelta / $slepicDelta * 100 - 100, 2) . "% slower then Slepic\n";