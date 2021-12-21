<?php
print('<pre>');
$start = microtime(true);

// print debug values?
$debug = true;

function _print($str){
	global $debug;
	if ($debug) print($str);
	ob_flush();
	flush();
}

$data = [];

$handle = fopen('input.txt', 'r');

while (($line = fgets($handle)) !== false) {

	if (!empty(trim($line))){
		
		$data[] = explode(' starting position: ', str_replace('Player ', '', trim($line)));
		
	}

}

fclose($handle);

$rolls = [
	'3' => 1,
	'4' => 3,
	'5' => 6,
	'6' => 7,
	'7' => 6,
	'8' => 3,
	'9' => 1,
];

$states = [
	[
		'player1_score' => 0,
		'player1_position' => $data[0][1],
		'player2_score' => 0,
		'player2_position' => $data[1][1],
		'count' => 1,
	]	
];

$wins1 = 0;
$wins2 = 0;

$limit = 21;

set_time_limit(36000);
$round = 0;

while(count($states)){

	$round++;
	
	// player 1 roll
	$new_states = [];
	foreach($rolls as $roll => $roll_count){
		
		_print(' p1 roll: '.$roll."\n");
		
		foreach($states as $state){
			
			$new_position = ($state['player1_position'] + $roll - 1) % 10 + 1;
			
			$new_state = [
				'player1_score' => $state['player1_score'] + $new_position,
				'player1_position' => $new_position,
				'player2_score' => $state['player2_score'],
				'player2_position' => $state['player2_position'],
				'count' => $state['count'] * $roll_count,
			];
			
			$found = false;
			foreach($new_states as $new_state_key => $new_state_check){
				
				if (
					$new_state_check['player1_score'] == $new_state['player1_score'] && 
					$new_state_check['player1_position'] == $new_state['player1_position'] && 
					$new_state_check['player2_score'] == $new_state['player2_score'] && 
					$new_state_check['player2_position'] == $new_state['player2_position']
				){
					$new_states[$new_state_key]['count'] += $new_state['count'];
					$found = true;
				}
				
			}
			
			if (!$found){
				$new_states[] = $new_state;
			}
							
		}
		
	}
	
	$states = $new_states;
	
	foreach($states as $state_key => $state_check){
			
		if ($state_check['player1_score'] >= $limit){
			$wins1 += $state_check['count'];
			unset($states[$state_key]);
		}
			
	}
	_print("\n");
	
	// player 2 roll
	$new_states = [];
	foreach($rolls as $roll => $roll_count){

		_print(' p2 roll: '.$roll."\n");
		
		foreach($states as $state){
				
			$new_position = ($state['player2_position'] + $roll - 1) % 10 + 1;
				
			$new_state = [
					'player2_score' => $state['player2_score'] + $new_position,
					'player2_position' => $new_position,
					'player1_score' => $state['player1_score'],
					'player1_position' => $state['player1_position'],
					'count' => $state['count'] * $roll_count,
			];
				
			$found = false;
			foreach($new_states as $new_state_key => $new_state_check){
	
				if (
						$new_state_check['player2_score'] == $new_state['player2_score'] &&
						$new_state_check['player2_position'] == $new_state['player2_position'] &&
						$new_state_check['player1_score'] == $new_state['player1_score'] &&
						$new_state_check['player1_position'] == $new_state['player1_position']
						){
							$new_states[$new_state_key]['count'] += $new_state['count'];
							$found = true;
				}
	
			}
				
			if (!$found){
				$new_states[] = $new_state;
			}
				
		}
	
	}
	
	$states = $new_states;
	
	foreach($states as $state_key => $state_check){
			
		if ($state_check['player2_score'] >= $limit){
			$wins2 += $state_check['count'];
			unset($states[$state_key]);
		}
			
	}
	_print("\n");
	
	_print($round.'. '.count($states).'('.$wins1.'/'.$wins2.')'."\n\n");

}

print("\n\n".round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(max($wins1, $wins2));
