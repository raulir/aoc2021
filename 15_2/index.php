<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			$row = str_split(trim($line));
			$data[] = $row;
		}

	}

	fclose($handle);

}

$max_x = count($data[0]);
$max_y = count($data);

$original = [];
foreach($data as $y => $row){
	foreach($row as $x => $point){

		for($i = 0; $i < 5; $i++){
			for($j = 0; $j < 5; $j++){
				
				$dr = $point + $i + $j;
				if ($dr > 9){
					$dr = $dr - 9;
				}
				if ($dr > 9){
					$dr = $dr - 9;
				}
				
				$oy = ($y + ($i * $max_y));
				$ox = ($x + ($j * $max_x));
				
				$original[(int)$oy][(int)$ox] = $dr;
				
			}
		}
		
	}
}

$data = $original;

ksort($data);
foreach($data as $y => &$rrow){
	ksort($rrow);
}

$total = 0;
$this_total = 1;
$danger_map = [];
$danger_map[0][0] = 0;

while ($total != $this_total){
	
	$total = $this_total;
	$this_total = 0;
	
	foreach ($data as $y => $row){
		foreach ($row as $x => $danger){
			
			if (!isset($danger_map[$y][$x])){
				$danger_map[$y][$x] = 9999;
			}
			
			$danger_here = min([
					($danger_map[$y - 1][$x] ?? 9999), 
					($danger_map[$y + 1][$x] ?? 9999), 
					($danger_map[$y][$x - 1] ?? 9999), 
					($danger_map[$y][$x + 1] ?? 9999),
			]) + $data[$y][$x];
			
			if ($danger_here < $danger_map[$y][$x]){
				$danger_map[$y][$x] = $danger_here;
			}
			$this_total += $danger_map[$y][$x];
			
		}
	}
	
}

$endrow = end($danger_map);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(end($endrow));