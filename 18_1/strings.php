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

/* up to base 59 functions */
function to_base($dec){
	
	if ($dec <= 35){
		return base_convert($dec, 10, 36);
	}
	
	return chr($dec - 36 + 65);
	
}

function from_base($base){
	
	$value = ord($base);
	
	if ($value < 58 || $value > 96){
		return base_convert($base, 36, 10);
	}

	return $value - 65 + 36;

}

/* addition */
function add($a, $b){
	
	if (empty($a)){
		return $b;
	}
	
	$return = '['.$a.','.$b.']';
	
	return $return;
	
}

/* split */
function split($a){
	
	$aa = str_split($a);
	
	foreach($aa as $ak => $av){
			
		if (ctype_alpha($av)){
			
			$value = from_base($av);
			$a = substr($a, 0, $ak).'['.to_base(floor($value/2)).','.to_base(ceil($value/2)).']'.substr($a, $ak + 1);
			
			_print(' split:  '.$a."\n");
			
			return $a;

		}
			
	}
	
	return $a;
	
}

/* explode */
function expl($a){
	
	$aa = str_split($a);
	
	// find max nesting depth for checking this depth first
	$depth = 0;
	$max_depth = 0;
	foreach($aa as $ak => $av){
			
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
	foreach($aa as $ak => $av){
			
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
		if (ctype_alnum($av) && !isset($reduce_point)){
			$last_numeric = $ak;
		}
		if (ctype_alnum($av) && isset($reduce_point) && $ak > ($reduce_point + 5) && !isset($next_numeric)){
			$next_numeric = $ak;
		}
			
	}
	
	if (isset($reduce_point)){
			
		$first_num = from_base($aa[$reduce_point + 1]);
		$second_num = from_base($aa[$reduce_point + 3]);
	
		if (isset($last_numeric)){
			$last_numeric_value = from_base($aa[$last_numeric]);
			$a = substr_replace($a, to_base($last_numeric_value + $first_num), $last_numeric, 1);
		}
			
		if (isset($next_numeric)){
			$next_numeric_value = from_base($aa[$next_numeric]);
			$a = substr_replace($a, to_base($next_numeric_value + $second_num), $next_numeric, 1);
		}
			
		$a = substr($a, 0, $reduce_point).'0'.substr($a, $reduce_point + 5);

		_print('  expl:  '.$a."\n");
		
	}
	
	return $a;

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

	return $a;
	
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
	
	$sum = add($sum, $number);
	
	_print('number:  '.$number."\n");
	_print('rawsum:  '.$sum."\n");
	
	$sum = reduce($sum);
	
	_print('sum:     '.$sum."\n");
	_print('magnit:  '.magnitude(json_decode($sum))."\n\n");
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(magnitude(json_decode($sum))."\n\n");

/* second part */
$start = microtime(true);

$max_magnitude = '';

foreach($data as $n1){
	foreach($data as $n2){

		if ($n1 !== $n2){
			$sum = reduce(add($n1, $n2));
			$max_magnitude = max(magnitude(json_decode($sum)), $max_magnitude);
			_print("\n");
		}

	}
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print($max_magnitude);
