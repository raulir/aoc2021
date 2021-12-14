<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			if (stristr($line, ' -> ')){
				list($pair, $insert) = explode(' -> ', trim($line));
				$data[$pair] = $insert;
			} else {
				$polymer = str_split(trim($line));
			}
		}
		
	}

	fclose($handle);

}

$pairs = [];
foreach($polymer as $key => $element){
	if(!empty($polymer[$key + 1])){
		$pairs[$element . $polymer[$key + 1]] = ($pairs[$element . $polymer[$key + 1]] ?? 0) + 1;
	}
}
	
for ($i = 0; $i < 40; $i++){
	
	$new_pairs = [];
	foreach($pairs as $pair => $count){
		
		if (!empty($data[$pair])){
			list($a, $b) = str_split($pair);
			if (empty($new_pairs[$a.$data[$pair]])){
				$new_pairs[$a.$data[$pair]] = 0;
			}
			$new_pairs[$a.$data[$pair]] += $count;
			if (empty($new_pairs[$data[$pair].$b])){
				$new_pairs[$data[$pair].$b] = 0;
			}
			$new_pairs[$data[$pair].$b] += $count;
		}
	
	}
	$pairs = $new_pairs;
	
}

$counts = [];
foreach($pairs as $pair => $count){
	
	list($a, $b) = str_split($pair);
	if (empty($counts[$a])){
		$counts[$a] = 0;
	}
	$counts[$a] += $count;
	if (empty($counts[$b])){
		$counts[$b] = 0;
	}
	$counts[$b] += $count;

}

$counts[$polymer[0]]++;
$counts[end($polymer)]++;

sort($counts);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print((end($counts) - $counts[0])/2);