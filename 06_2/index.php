<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty($line)){
			$data = explode(',', trim($line));
		}
		
	}

	fclose($handle);

}

$fishes = [0,0,0,0,0,0,0,0,0];

foreach($data as $key => $fish){

	$fishes[$fish] = ($fishes[$fish] ?? 0) + 1;

}

print(implode(' , ', $fishes)."\n");

for ($i = 0; $i < 256; $i++){
	
	$newfishes = [0,0,0,0,0,0,0,0,0];
	
	foreach($fishes as $key => $number){
		if($key != 0){
			$newfishes[$key - 1] += $number;
		} else {
			$newfishes[8] += $number;
			$newfishes[6] += $number;
		}
	}
	
	$fishes = $newfishes;
	
	print(implode(' , ', $fishes)."\n");

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print(array_sum($fishes));