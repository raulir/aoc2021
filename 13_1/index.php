<?php
print('<pre>');
$start = microtime(true);
$data = [];
$folds = [];
$max_x = 0;
$max_y = 0;

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			if (stristr($line, ',')){
				list($x, $y) = explode(',', trim($line));
				if (empty($data[$y])) $data[$y] = [];
				$data[$y][$x] = 1;
			} else {
				if (stristr($line, 'x')){
					list($rest, $value) = explode('=', trim($line));
					$folds[] = ['x' => $value];
					$max_x = max($max_x, $value);
				} else {
					list($rest, $value) = explode('=', trim($line));
					$folds[] = ['y' => $value];
					$max_y = max($max_y, $value);
				}
			}
		}
		
	}

	fclose($handle);

}

$size_x = $max_x * 2 + 1;
$size_y = $max_y * 2 + 1;

$counts = [];
foreach($folds as $fold){
	
	if(!empty($fold['x'])){
		
		$size_x = $fold['x'] - 1;
		
		foreach($data as $y => $row){

			foreach($data[$y] as $x => $point){
				if ($x > $fold['x']){
					$data[$y][2 * $fold['x'] - $x] = 1;
				}
			}

		}

	}
	
	$n = 0;
	for ($y = 0; $y <= $size_y; $y++){
		for ($x = 0; $x <= $size_x; $x++){
			if(!empty($data[$y][$x])){
				$n++;
			}
		}
	}
	$counts[] = $n;

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print_r($counts[0]);