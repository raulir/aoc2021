<?php

$depth = 0;
$position = 0;

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		list($command, $amount) = explode(' ', $line);
		
		if ($command == 'forward'){
			$position += $amount;
		} else if ($command == 'down'){
			$depth += $amount;
		} elseif ($command == 'up'){
			$depth -= $amount;
		} 
		
	}

	fclose($handle);
}

print($depth * $position);