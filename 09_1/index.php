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

foreach($data as $key => $row){
	
	foreach($row as $ikey => $item){
		
		if (	($data[$key + 1][$ikey] ?? 10) > $item && 
				($data[$key - 1][$ikey] ?? 10) > $item && 
				($data[$key][$ikey + 1] ?? 10) > $item && 
				($data[$key][$ikey - 1] ?? 10) > $item){
			
			$sum += $item + 1;
		}
		
	}

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($sum);