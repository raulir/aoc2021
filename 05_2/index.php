<?php
print('<pre>');
$start = microtime(true);

$data = [];

foreach(explode("\n", trim(file_get_contents('input.txt'))) as $line){
	list($r['x1'],$r['y1'],$r['x2'],$r['y2']) = preg_split('/( -> |,)/', $line);
	$data[] = $r;
}

$field = [];

foreach($data as $record){
	if ($record['x1'] == $record['x2']){
		
		for ($i = min($record['y1'], $record['y2']); $i <= max($record['y1'], $record['y2']); $i++){
			$field[$record['x1']][$i] = ($field[$record['x1']][$i] ?? 0) + 1;
		}
		
	} else if ($record['y1'] == $record['y2']){
		
		for ($i = min($record['x1'], $record['x2']); $i <= max($record['x1'], $record['x2']); $i++){
			$field[$i][$record['y1']] = ($field[$i][$record['y1']] ?? 0) + 1;
		}
		
	} else {
		
		$dx = $record['x2'] > $record['x1'] ? 1 : -1;
		$dy = $record['y2'] > $record['y1'] ? 1 : -1;

		for($i = 0; $i <= abs($record['x2'] - $record['x1']); $i++){
			$nx = $record['x1'] + $i*$dx;
			$ny = $record['y1'] + $i*$dy;
			$field[$nx][$ny] = ($field[$nx][$ny] ?? 0) + 1;
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
print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($number);