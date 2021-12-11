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

$ends = [
		')' => '(',
		']' => '[',
		'}' => '{',
		'>' => '<',
];

$scores = [
		')' => 3,
		']' => 57,
		'}' => 1197,
		'>' => 25137,
];

$score = 0;

foreach($data as $line){
	
	$stack = [];
	
	foreach($line as $character){

		if (in_array($character, ['(', '[', '{', '<'])){
			$stack[] = $character;
		} else if (end($stack) == $ends[$character]){// in_array($ends[$character], $stack)){
			// $key = array_search($ends[$character], array_reverse($stack, true));
			array_pop($stack);
		} else {
			$score += $scores[$character];
			break;
		}
		
	}

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($score);