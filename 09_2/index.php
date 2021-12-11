<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty($line)){
			$line_array = str_split(trim($line));
		}
		
		$data[] = $line_array;
		
	}

	fclose($handle);

}

$sum = 0;

$basins = [];
$basin_i = 1;

foreach($data as $key => $row){
	
	foreach($row as $ikey => $item){
		
		if (	($data[$key + 1][$ikey] ?? 10) > $item && 
				($data[$key - 1][$ikey] ?? 10) > $item && 
				($data[$key][$ikey + 1] ?? 10) > $item && 
				($data[$key][$ikey - 1] ?? 10) > $item){
			
			$sum += $item + 1;
			$basins[$key][$ikey] = $basin_i;
			$basin_i ++;
			
		}
		
	}

}

$sums = [];

$added = 1;
while($added > 0){
	
	$added = 0;
	
	foreach($data as $key => $row){
	
		foreach($row as $ikey => $item){
	
			if ($item < 9 && empty($basins[$key][$ikey])){
				
				if(!empty($basins[$key + 1][$ikey])){
					$basins[$key][$ikey] = $basins[$key + 1][$ikey];
					$added++;
					$sums[$basins[$key][$ikey]] = ($sums[$basins[$key][$ikey]] ?? 1) + 1;
				} else if(!empty($basins[$key - 1][$ikey])){
					$basins[$key][$ikey] = $basins[$key - 1][$ikey];
					$added++;
					$sums[$basins[$key][$ikey]] = ($sums[$basins[$key][$ikey]] ?? 1) + 1;
				} else if(!empty($basins[$key][$ikey + 1])){
					$basins[$key][$ikey] = $basins[$key][$ikey + 1];
					$added++;
					$sums[$basins[$key][$ikey]] = ($sums[$basins[$key][$ikey]] ?? 1) + 1;
				} else if(!empty($basins[$key][$ikey - 1])){
					$basins[$key][$ikey] = $basins[$key][$ikey - 1];
					$added++;
					$sums[$basins[$key][$ikey]] = ($sums[$basins[$key][$ikey]] ?? 1) + 1;
				}
				
			}
	
		}
	
	}
	
}
/*
foreach($data as $key => $row){
	foreach($row as $ikey => $item){
		if (!empty($basins[$key][$ikey])){
			print('<b>'.$item.'</b>');
		} else {
			print($item);
		}
	}
	print("\n");
}
*/
sort($sums);
$sums = array_reverse($sums);

// print_r($sums);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($sums[0] * $sums[1] * $sums[2]);