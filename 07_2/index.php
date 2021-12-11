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

$crabs = [];
$max = 0;

foreach($data as $crab){

	$crabs[$crab] = ($crabs[$crab] ?? 0) + 1;
	
	$max = max($max, $crab);

}

// print_r($crabs);

// print(implode(' , ', $crabs)."\n");
$minfuel = 10000000000000000000;
for ($i = 0; $i <= $max; $i++){
	
	$fuel = 0;
	
	foreach($crabs as $pos => $number){
		
		$delta = abs($i - $pos);
		$fuel += (($delta + 1)*$delta )/2 * $number;
		
	}
	
	$minfuel = min($fuel, $minfuel);
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($minfuel);