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

for ($i = 0; $i < 10; $i++){
	$new_polymer = [];
	foreach($polymer as $key => $element){
		
		$new_polymer[] = $element;
		if(!empty($polymer[$key + 1])){
			$new_polymer[] = $data[$element . $polymer[$key + 1]] ?? '';
		}
	
	}
	$polymer = $new_polymer;
}

$values = array_count_values($polymer);
sort($values);
print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(end($values) - $values[0]);