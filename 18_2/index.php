<?php
print('<pre>');
$start = microtime(true);

$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			$data[] = trim($line);
		}

	}

	fclose($handle);

}

function add($a, $b){

	if (empty($a[0])){
		return $b;
	}
	
	$return = ['[', ...$a, ',', ...$b, ']'];
	
	return $return;
	
}

function split($a){
	
	foreach($a as $ak => $av){
			
		if (is_numeric($av) && $av >= 10){
			
			$return = [...array_slice($a, 0, $ak), '[', floor($av/2), ',', ceil($av/2), ']', ...array_slice($a, $ak+1)];
			
			return $return;

		}
			
	}
	
	return $a;
	
}

function expl($a){

	$depth = 0;
	$max_depth = 0;
	foreach($a as $ak => $av){
			
		if ($av == '['){
			$depth++;
			$max_depth = max($max_depth, $depth);
		} elseif ($av == ']'){
			$depth--;
		}
	
	}
	
	if ($max_depth <= 4){
		return $a;
	}

	$depth = 0;
	foreach($a as $ak => $av){
			
		if ($av == '['){
			$depth++;
		} elseif ($av == ']'){
			$depth--;
		}
			
		if ($depth == $max_depth && $av == '[' && !isset($reduce_point)){
			$reduce_point = $ak;
		}
			
		if (is_numeric($av) && !isset($reduce_point)){
			$last_numeric = $ak;
		}
		if (is_numeric($av) && isset($reduce_point) && $ak > ($reduce_point + 5) && !isset($next_numeric)){
			$next_numeric = $ak;
		}
			
	}
	
	if (isset($reduce_point)){

		if (isset($last_numeric)){
			$a[$last_numeric] = $a[$last_numeric] + $a[$reduce_point + 1];
		}
			
		if (isset($next_numeric)){
			$a[$next_numeric] = $a[$next_numeric] + $a[$reduce_point + 3];
		}
		
		$a[$reduce_point] = 0;
		unset($a[$reduce_point + 1]);
		unset($a[$reduce_point + 2]);
		unset($a[$reduce_point + 3]);
		unset($a[$reduce_point + 4]);

	}
	
	return array_values($a);

}

function reduce($a){
	
	$as = '';
	while ($as != $a){
		
		$as = $a;
		$a = split($a);

		$ao = '';
		while ($ao != $a){
			$ao = $a;
			$a = expl($a);
		}
	
	}

	return implode('', $a);
	
}

function magnitude($a){
	
	if (is_array($a[0])){
		$a[0] = magnitude(array_values($a[0]));
	}
	
	if (is_array($a[1])){
		$a[1] = magnitude(array_values($a[1]));
	}
	
	return 3 * (int)$a[0] + 2 * (int)$a[1];
	
}

$max_magnitude = '';

foreach($data as $n1){
	foreach($data as $n2){
	
		if ($n1 !== $n2){
			$sum_a = add(str_split($n1), str_split($n2));
			$sum = reduce($sum_a);
			$max_magnitude = max(magnitude(json_decode($sum)), $max_magnitude);
		}
		
	}
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print_r($max_magnitude);
