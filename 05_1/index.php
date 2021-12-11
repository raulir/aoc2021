<?php

$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
		
			$line_tmp = explode(' -> ', trim($line));

			list($record['x1'], $record['y1']) = explode(',', $line_tmp[0]);
			list($record['x2'], $record['y2']) = explode(',', $line_tmp[1]);
				
		}
		
		$data[] = $record;
		
	}

	fclose($handle);
}

$field = [];

foreach($data as $record){
	if ($record['x1'] == $record['x2']){
		
		for ($i = min($record['y1'], $record['y2']); $i <= max($record['y1'], $record['y2']); $i++){
			if (!isset($field[$record['x1']])){
				$field[$record['x1']] = [];
			}
			if (!isset($field[$record['x1']][$i])){
				$field[$record['x1']][$i] = 0;
			}
			$field[$record['x1']][$i]++;
		}
		
	}
	
	if ($record['y1'] == $record['y2']){
		
		for ($i = min($record['x1'], $record['x2']); $i <= max($record['x1'], $record['x2']); $i++){
			if (!isset($field[$i])){
				$field[$i] = [];
			}
			if (!isset($field[$i][$record['y1']])){
				$field[$i][$record['y1']] = 0;
			}
			$field[$i][$record['y1']]++;
		}
		
	}
}

$number = 0;
foreach($field as $row){
	foreach($row as $point){
		if($point > 1){
			$number++;
		}
	}	
}

print($number);