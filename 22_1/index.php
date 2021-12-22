<?php
print('<pre>');
$start = microtime(true);

// print debug values?
$debug = true;

function _print($str){
	global $debug;
	if ($debug) {
		print_r($str);
	}
	ob_flush();
	flush();
}

$data = [];

$handle = fopen('input.txt', 'r');

while (($line = fgets($handle)) !== false) {

	$line = trim($line);
		
	if (!empty($line)){
		
		list($do, $rest) = explode(' ', $line);
		
		list($xstr,$ystr, $zstr) = explode(',', $rest);
		
		$row = [
			'do' => ($do == 'on' ? 1 : 0),
		];
		
		list($row['x1'], $row['x2']) = explode('..', str_replace('x=', '', $xstr));
		list($row['y1'], $row['y2']) = explode('..', str_replace('y=', '', $ystr));
		list($row['z1'], $row['z2']) = explode('..', str_replace('z=', '', $zstr));
		
		$data[] = $row;
		
	}

}

fclose($handle);

foreach($data as $key => $step){
	
	if ($step['x1'] > 50 || $step['x2'] < -50) break;
	if ($step['x1'] < -50) $step['x1'] = -50;
	if ($step['x2'] > 50) $step['x2'] = 50;
	
	if ($step['y1'] > 50 || $step['y2'] < -50) break;
	if ($step['y1'] < -50) $step['y1'] = -50;
	if ($step['y2'] > 50) $step['y2'] = 50;
	
	if ($step['y1'] > 50 || $step['y2'] < -50) break;
	if ($step['y1'] < -50) $step['y1'] = -50;
	if ($step['y2'] > 50) $step['y2'] = 50;
	
	for($x = $step['x1']; $x <= $step['x2']; $x++){
		for($y = $step['y1']; $y <= $step['y2']; $y++){
			for($z = $step['z1']; $z <= $step['z2']; $z++){
				$field[$z.'_'.$y.'_'.$x] = $step['do'];
				
			}
		}
	}
	
}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print(array_sum($field));
