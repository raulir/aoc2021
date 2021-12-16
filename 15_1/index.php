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
				$danger_map[$y][$x] = 10000;
			}
			
			$danger_here = min([
					($danger_map[$y - 1][$x] ?? 10000), 
					($danger_map[$y + 1][$x] ?? 10000), 
					($danger_map[$y][$x - 1] ?? 10000), 
					($danger_map[$y][$x + 1] ?? 10000),
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