<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty($line)){
			$parts = explode(' | ', trim($line));
			
			$row['lines'] = explode(' ', $parts[0]);
			$row['digits'] = explode(' ', $parts[1]);
		}
		
		$data[] = $row;
		
	}

	fclose($handle);

}

$count = 0;

foreach($data as $display){

	foreach($display['digits'] as $digit){
		
		if (in_array(strlen($digit), [2,3,4,7])){
			
			$count++;
			
		}
		
	}
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($count);