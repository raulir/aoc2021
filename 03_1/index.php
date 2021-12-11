<?php

$ones = [];
$count = 0;

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		$line_array = str_split($line);
		
		foreach($line_array as $key => $character){
			if(!isset($ones[$key])) $ones[$key] = 0;
			$ones[$key] += intval($character);
		}
		
		$count++;
		
	}

	fclose($handle);
}

$firstnumberbin = '';
$secondnumberbin = '';

foreach($ones as $one){
	if (!empty($one)){
		if($one > $count/2){
			$firstnumberbin .= '1';
			$secondnumberbin .= '0';
		} else {
			$firstnumberbin .= '0';
			$secondnumberbin .= '1';
		}
	}
}

print(bindec($firstnumberbin) * bindec($secondnumberbin));