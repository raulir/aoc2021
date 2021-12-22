<?php
print('<pre>');
$start = microtime(true);

// print max debug level values?
$debug = 1;

function _print($str, $level = 1){
	global $debug;
	if ($debug >= $level) {
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
			'value' => ($do == 'on' ? 1 : 0),
		];
		
		list($row['x1'], $row['x2']) = explode('..', str_replace('x=', '', $xstr));
		list($row['y1'], $row['y2']) = explode('..', str_replace('y=', '', $ystr));
		list($row['z1'], $row['z2']) = explode('..', str_replace('z=', '', $zstr));
		
		$data[] = $row;
		
	}

}

fclose($handle);

$cubes[0] = [
	'x1' => -1000000,
	'x2' => 1000000,
	'y1' => -1000000,
	'y2' => 1000000,
	'z1' => -1000000,
	'z2' => 1000000,
	'value' => 0,
];

foreach($data as $key => $step){
	
	foreach($cubes as $cube_id => $cube){
		
		if ($step['value']!= $cube['value']) {
			
			// if covers fully
			if (($step['x1'] <= $cube['x1'] && $step['x2'] >= $cube['x2']) &&
				($step['y1'] <= $cube['y1'] && $step['y2'] >= $cube['y2']) &&
				($step['z1'] <= $cube['z1'] && $step['z2'] >= $cube['z2'])){
				
				$cubes[$cube_id]['value'] = $step['value'];
_print('xyz ', 2);

			} else {
			
				$temp = [];
				$updated = false;
				$to_work = $cube;
				
				// if x needs shaving from beginning
				if ($step['x1'] > $to_work['x1'] && $step['x1'] <= $to_work['x2']
						&& ($to_work['y1'] <= $step['y2'] && $to_work['y2'] >= $step['y1'])
						&& ($to_work['z1'] <= $step['z2'] && $to_work['z2'] >= $step['z1'])){
_print('xb ', 2);				
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['x1'] = $step['x1'];
					$cubes[$cube_id]['x2'] = $step['x1'] - 1;
					$updated = true;
					
					$to_work = $temp[array_key_last($temp)];
				}
				
				// if x needs shaving from end
				if ($step['x2'] < $to_work['x2'] && $step['x2'] >= $to_work['x1']
						&& ($to_work['y1'] <= $step['y2'] && $to_work['y2'] >= $step['y1'])
						&& ($to_work['z1'] <= $step['z2'] && $to_work['z2'] >= $step['z1'])){
_print('xe ', 2);
					$prev_temp_id = array_key_last($temp);
					
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['x2'] = $step['x2'];
					if (!$updated){
						$cubes[$cube_id]['x1'] = $step['x2'] + 1;
						$updated = true;
					} else {
						$temp[$prev_temp_id]['x1'] = $step['x2'] + 1;
					}
					
					$to_work = $temp[array_key_last($temp)];
					
				}
				
				// if y needs shaving from beginning
				if ($step['y1'] > $to_work['y1'] && $step['y1'] <= $to_work['y2']
						&& ($to_work['x1'] <= $step['x2'] && $to_work['x2'] >= $step['x1'])
						&& ($to_work['z1'] <= $step['z2'] && $to_work['z2'] >= $step['z1'])){
_print('yb ', 2);
					$prev_temp_id = array_key_last($temp);
				
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['y1'] = $step['y1'];
					if (!$updated){
						$cubes[$cube_id]['y2'] = $step['y1'] - 1;
						$updated = true;
					} else {
						$temp[$prev_temp_id]['y2'] = $step['y1'] - 1;
					}
				
					$to_work = $temp[array_key_last($temp)];
				
				}
				
				// if y needs shaving from end
				if ($step['y2'] < $to_work['y2'] && $step['y2'] >= $to_work['y1']
						&& ($to_work['x1'] <= $step['x2'] && $to_work['x2'] >= $step['x1'])
						&& ($to_work['z1'] <= $step['z2'] && $to_work['z2'] >= $step['z1'])){
_print('ye ', 2);
					$prev_temp_id = array_key_last($temp);
				
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['y2'] = $step['y2'];
					if (!$updated){
						$cubes[$cube_id]['y1'] = $step['y2'] + 1;
						$updated = true;
					} else {
						$temp[$prev_temp_id]['y1'] = $step['y2'] + 1;
					}
				
					$to_work = $temp[array_key_last($temp)];
				
				}
	
				// if z needs shaving from beginning
				if ($step['z1'] > $to_work['z1'] && $step['z1'] <= $to_work['z2']
						&& ($to_work['x1'] <= $step['x2'] && $to_work['x2'] >= $step['x1'])
						&& ($to_work['y1'] <= $step['y2'] && $to_work['y2'] >= $step['y1'])){
_print('zb ', 2);			
					$prev_temp_id = array_key_last($temp);
				
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['z1'] = $step['z1'];
					if (!$updated){
						$cubes[$cube_id]['z2'] = $step['z1'] - 1;
						$updated = true;
					} else {
						$temp[$prev_temp_id]['z2'] = $step['z1'] - 1;
					}
				
					$to_work = $temp[array_key_last($temp)];
				
				}
				
				// if z needs shaving from end
				if ($step['z2'] < $to_work['z2'] && $step['z2'] >= $to_work['z1']
						&& ($to_work['x1'] <= $step['x2'] && $to_work['x2'] >= $step['x1'])
						&& ($to_work['y1'] <= $step['y2'] && $to_work['y2'] >= $step['y1'])){
_print('ze ', 2);			
					$prev_temp_id = array_key_last($temp);
				
					$temp[] = $to_work;
					$temp[array_key_last($temp)]['z2'] = $step['z2'];
					if (!$updated){
						$cubes[$cube_id]['z1'] = $step['z2'] + 1;
						$updated = true;
					} else {
						$temp[$prev_temp_id]['z1'] = $step['z2'] + 1;
					}
				
					$to_work = $temp[array_key_last($temp)];
				}
				
				if (count($temp)){
					$temp[array_key_last($temp)]['value'] = $step['value'];
					$cubes = array_merge($cubes, $temp);
				}
			
			}
			
		}
		
	}

_print("\n", 2);
_print('step: ' . $key . '/'.(count($data)-1).' - cubes: '.count($cubes)."\n");

}

$sum = 0;
$count = 0;
foreach($cubes as $cube){
	if ($cube['value'] == 1){
		$sum+= ($cube['x2'] - $cube['x1'] + 1)*($cube['y2'] - $cube['y1'] + 1)*($cube['z2'] - $cube['z1'] + 1);
		$count++;
	}
}
_print($count."\n");

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print($sum);
