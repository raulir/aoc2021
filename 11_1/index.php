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

$score = 0;

for($i = 0; $i < 100; $i++){
	
	foreach($data as $x => $line){

		foreach($line as $y => $octopus){
	
			$data[$x][$y] += 1;
			
		}
	
	}
		
	$flashes = -1;

	while ($flashes != 0){
		
		$flashes = 0;
		
		foreach($data as $x => $line){

			foreach($line as $y => $octopus){
		
				if ($octopus > 9){
					$data[$x][$y] = 0;
					$flashes++;
					$score++;
					for ($dx = -1; $dx <= 1; $dx++){
						for ($dy = -1; $dy <= 1; $dy++){
							if (!empty($data[$x + $dx][$y + $dy])){
								$data[$x + $dx][$y + $dy]++;
							}
						}
					} 
				}
					
			}
		
		}
		
	}

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($score);