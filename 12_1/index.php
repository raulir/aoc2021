<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			$data[] = explode('-', trim($line));
		}
		
	}

	fclose($handle);

}

// exits by room
$exits = [];
foreach($data as $item){
	
	if (empty($exits[$item[0]])){
		$exits[$item[0]] = [$item[1]];
	} else {
		$exits[$item[0]][] = $item[1];
	}
	
	if (empty($exits[$item[1]])){
		$exits[$item[1]] = [$item[0]];
	} else {
		$exits[$item[1]][] = $item[0];
	}
	
}

$begin = 'start';
$end = 'end';

function step($path){

	global $exits, $end;
	
	$return = [];

	// get available exits from room
	$visited = explode(',', $path);
	
	$current = end($visited);
	
	$visited = array_filter($visited, function ($string) {
    	return $string === strtolower($string);
	});

	if ($current == $end){
			
		$return[] = $path;
		
	} else {
		
		foreach($exits[$current] as $exit){
			
			if (!in_array($exit, $visited)){
	
				$return = array_merge($return, step($path.','.$exit));
			
			}
		}
		
	}

	return $return;
	
}

$paths = step('start');

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print(count($paths));