<?php

print('<pre>');

$numbers = [];
$boards = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	$numbersline = fgets($handle);
	$numbers = explode(',', trim($numbersline));
	
	$board = [];
	$line_number = 0;
	
	while (($line = fgets($handle)) !== false) {

		$line_numbers = [];
		$line_data = explode(' ', $line);

			foreach($line_data as $number){
			if (trim($number) !== ''){
				$line_numbers[(int)$number] = 0;
			}
		}
		
		if (!empty(count($line_numbers))){
			$board[] = $line_numbers;
		}

		if (empty(trim($line))){
				
			if (!empty($board)){
				$boards[] = $board;
				$board = [];
			}
		}

	}

	fclose($handle);
}

foreach($numbers as $number){

	foreach($boards as $board_number => $board){
		
		foreach($board as $line_number => $line){
			foreach($line as $key => $value){
				if ($key == $number){
					$boards[$board_number][$line_number][$key] = 1;
				}
			}
		}
		
	}
	
	foreach($boards as $board_number => $board){
		
		$won = false;
		$cols = [0,0,0,0,0];
		
		foreach($board as $line){
			if (array_sum($line) == 5){
				$won = true;
			}
		}
		
		$c = array_map(function (...$arrays) {
			return array_sum($arrays);
		}, ...$board);
		
		if (in_array(5, $c)){
			$won = true;
		}

		if($won){
			
			
			$board_sum = 0;
			foreach($board as $line){
				foreach($line as $key => $value){
					if (!$value){
						$board_sum += $key;
					}
				}
			}

			print($board_sum * $number);

			exit();
			
		}
		
	}
	
}
