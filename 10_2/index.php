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
		'(' => 1,
		'[' => 2,
		'{' => 3,
		'<' => 4,
];

$stacks = [];

$score = 0;

$line_scores = [];

foreach($data as $line_number => $line){
	
	$stack = [];
	
	foreach($line as $character){

		if (in_array($character, ['(', '[', '{', '<'])){
			$stack[] = $character;
		} else if (end($stack) == $ends[$character]){
			array_pop($stack);
		} else {
			unset($data[$line_number]);
			break;
		}
		
	}
	
	if(!empty($stack)){
		$stacks[$line_number] = $stack;
	}
	
}

foreach($data as $line_number => $line){
	
	$stack = $stacks[$line_number];

	$line_score = 0;
	foreach(array_reverse($stack) as $item){
		$line_score = $line_score * 5 + $scores[$item];
	}
	
	$line_scores[] = $line_score;

}

sort($line_scores);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($line_scores[count($line_scores)/2]);