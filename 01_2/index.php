<?php

$prev1 = -1;
$prev2 = -1;

$sums = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	while (($line = fgets($handle)) !== false) {
		if ($prev1 > 0 && $prev2 > 0){
			$sums[] = $prev1 + $prev2 + $line;
		}
		$prev1 = $prev2;
		$prev2 = $line;
	}

	fclose($handle);
}

$prev = -1;

$inc = 0;

foreach ($sums as $sum) {
	if ($prev > 0){
		if ($sum > $prev){
			$inc++;
		}
	}
	$prev = $sum;
}

print($inc);