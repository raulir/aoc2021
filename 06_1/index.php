<?php
print('<pre>');
$start = microtime(true);
$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty($line)){
			$data = explode(',', trim($line));
		}
		
	}

	fclose($handle);

}

for ($i = 0; $i < 80; $i++){
	
	foreach($data as $key => $fish){
		
		if ($fish == 0){
			$data[] = 8;
			$data[$key] = 6;
		} else {
			$data[$key] -= 1;
		}
		
	}

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print(count($data));