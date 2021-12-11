<?php

$prev = -1;

$inc = 0;
$dec = 0;

$handle = fopen('input.txt', 'r');
if ($handle) {
	while (($line = fgets($handle)) !== false) {
		if ($prev > 0){
			if ($line > $prev){
				$inc++;
			}
		}
		$prev = $line;
	}

	fclose($handle);
}

print($inc);