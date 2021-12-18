<?php
print('<pre>');
$start = microtime(true);

// print debug values?
$debug = false;

function _print($str){
	global $debug;
	if ($debug) print($str);
}

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

/* addition */
function add($a, $b){

	if (empty($a[0])){
		return $b;
	}
	
	$return = ['[', ...$a, ',', ...$b, ']'];
	
	return $return;
	
}

/* split */
function split($a){
	
	foreach($a as $ak => $av){
			
		if (is_numeric($av) && $av >= 10){
			
			$return = [...array_slice($a, 0, $ak), '[', floor($av/2), ',', ceil($av/2), ']', ...array_slice($a, $ak+1)];
			
			_print(' split:  '.implode('', $return)."\n");
				
			return $return;

		}
			
	}
	
	return $a;
	
}

/* explode */
function expl($a){

	// find max nesting depth for checking this depth first
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
	
	// if max depth ok, just return
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
			
		// if max depth - this point needs to be reduced
		if ($depth == $max_depth && $av == '[' && !isset($reduce_point)){
			$reduce_point = $ak;
		}
			
		// remember numbers around
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
		
		_print('  expl:  '.implode('', $a)."\n");
		
	}
	
	return array_values($a);

}

/* reduce */
function reduce($a){
	
	// explode until nothing changes
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

/* magnitude */
function magnitude($a){
	
	if (is_array($a[0])){
		$a[0] = magnitude(array_values($a[0]));
	}
	
	if (is_array($a[1])){
		$a[1] = magnitude(array_values($a[1]));
	}
	
	return 3 * (int)$a[0] + 2 * (int)$a[1];
	
}

// '' is zero
$sum = '';

// loop
foreach($data as $key => $number){
	
	$sum_a = add(str_split($sum), str_split($number));
	
	_print('number:  '.$number."\n");
	_print('rawsum:  '.implode('', $sum_a)."\n");
	
	$sum = reduce($sum_a);
	
	_print('sum:     '.$sum."\n");
	_print('magnit:  '.magnitude(json_decode($sum))."\n\n");
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(magnitude(json_decode($sum)));
