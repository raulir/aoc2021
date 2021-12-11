<?php

$depth = 0;
$position = 0;
$aim = 0;

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		list($command, $amount) = explode(' ', $line);
		
		if ($command == 'forward'){
			$position += $amount;
			$depth += $amount * $aim;
		} else if ($command == 'down'){
			$aim += $amount;
		} elseif ($command == 'up'){
			$aim -= $amount;
		} 
		
	}

	fclose($handle);
}

print($depth * $position);