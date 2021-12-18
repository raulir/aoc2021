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
			
			$value = base_convert($av, 35, 10);
			$a = substr($a, 0, $ak).'['.base_convert(floor($value/2), 10, 35).','.base_convert(ceil($value/2), 10, 35).']'.substr($a, $ak + 1);
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
			
		$first_num = base_convert($aa[$reduce_point + 1], 35, 10);
		$second_num = base_convert($aa[$reduce_point + 3], 35, 10);
	
		if (isset($last_numeric)){
			$last_numeric_value = base_convert($aa[$last_numeric], 35, 10);
			$a = substr_replace($a, base_convert($last_numeric_value + $first_num, 10, 35), $last_numeric, 1);
		}
			
		if (isset($next_numeric)){
			$next_numeric_value = base_convert($aa[$next_numeric], 35, 10);
			$a = substr_replace($a, base_convert($next_numeric_value + $second_num, 10, 35), $next_numeric, 1);
		}
			
		$a = substr($a, 0, $reduce_point).'0'.substr($a, $reduce_point + 5);

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

		// split
		
		print(' split:  '.$a."\n");
		
		$ao = '';
		while ($ao != $a){
			$ao = $a;
			$a = expl($a);
			print('  expl:  '.$a."\n");
		}
	
	}

	return $a;
	
}

// '' is zero
$sum = '';

// loop
foreach($data as $key => $number){
	
	$sum = add($sum, $number);
	
	print('number:  '.$number."\n");
	print('rawsum:  '.$sum."\n");
	
	$sum = reduce($sum);
	
	print('sum:     '.$sum."\n\n");
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print_r('');
