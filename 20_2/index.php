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

$filter = str_split(trim(fgets($handle)));

for($i = 0; $i < 200; $i++){
	$data[] = str_split(str_repeat('.', 600));
}

while (($line = fgets($handle)) !== false) {

	if (!empty(trim($line))){
		$data[] = str_split(trim(str_repeat('.', 200).trim($line).str_repeat('.', 300)));
	}

}

for($i = 0; $i < 200; $i++){
	$data[] = str_split(str_repeat('.', 600));
}

fclose($handle);

for($i = 0; $i < 50; $i++){
	
	$new_data = [];

	foreach($data as $y => $row){
		foreach($row as $x => $pixel){
		
			$position = ($data[$y - 1][$x - 1] ?? '.').($data[$y - 1][$x] ?? '.').($data[$y - 1][$x + 1] ?? '.').
					($data[$y][$x - 1] ?? '.').($data[$y][$x] ?? '.').($data[$y][$x + 1] ?? '.').
					($data[$y + 1][$x - 1] ?? '.').($data[$y + 1][$x] ?? '.').($data[$y + 1][$x + 1] ?? '.');
			$position = bindec(str_replace(['.','#'], ['0','1'], $position));
				
			$new_data[$y][$x] = $filter[$position];
			
		}
	}

	$data = $new_data;
	
}

$sum = 0;
for($y = 100; $y < 400; $y++){
	for($x = 100; $x < 400; $x++){
		if (($data[$y][$x] ?? '.') == '#') $sum++;
	}
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print($sum);
