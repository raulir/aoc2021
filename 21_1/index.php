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

while (($line = fgets($handle)) !== false) {

	if (!empty(trim($line))){
		
		$row = explode(' starting position: ', str_replace('Player ', '', trim($line)));
		
		$players[$row[0]] = $row[1];
		$scores[$row[0]] = 0;
		
	}

}

fclose($handle);

$dice = 1;
$dice_counter = 0;

while(true){
	

	foreach($players as $player_id => $player_position){
		
		$dice_sum = 0;
		for($i = 0; $i < 3; $i++){
			
			$dice_sum += $dice;
			$dice++;
			$dice_counter++;
			if ($dice > 100){
				$dice = 1;
			}
			
		}
		
		$players[$player_id] += $dice_sum;
		if ($players[$player_id] > 10){
			$players[$player_id] = (($players[$player_id] - 1) % 10) + 1;
		}
		
		$scores[$player_id] += $players[$player_id];
		
		if ($scores[$player_id] >= 1000){
			break 2;
		}

	}
	
}

sort($scores);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print($scores[0] * $dice_counter);
