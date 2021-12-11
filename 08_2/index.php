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

$sum = 0;

foreach($data as $key => $display){

	$displaylines = $display['lines'];
	
	usort($displaylines, function ($a, $b) {
		
		$alen = strlen($a);
		$blen = strlen($b);
		
		if ($alen == 5) $alen = 6;
		elseif ($alen == 6) $alen = 5;
		
		if ($blen == 5) $blen = 6;
		elseif ($blen == 6) $blen = 5;
		return $alen > $blen;
	});

	$digits = [];
	foreach($displaylines as $digit){
		
		$compdigit = str_split($digit);
		sort($compdigit);
				
		if (strlen($digit) == 2){
			
			$digits[1] = $compdigit;
			
		}
		
		if (strlen($digit) == 3){
			
			$digits[7] = $compdigit;
			
		}
		
		if (strlen($digit) == 4){
			
			$digits[4] = $compdigit;
			
		}
		
		if (strlen($digit) == 7){
			
			$digits[8] = $compdigit;
			
		}
		
		if (strlen($digit) == 6){

			if (empty(array_diff($digits[4], $compdigit))){
				$digits[9] = $compdigit;
			} elseif (empty(array_diff($digits[7], $compdigit))){
				$digits[0] = $compdigit;
			} else {
				$digits[6] = $compdigit;
			}

		}

		if (strlen($digit) == 5){

			if (empty(array_diff($digits[1], $compdigit))){
				$digits[3] = $compdigit;
			} elseif (empty(array_diff($compdigit, $digits[6]))){
				$digits[5] = $compdigit;
			} else {
				$digits[2] = $compdigit;
			}
		
		}
		
	}
	
	$table = [];
	foreach($digits as $value => $digitarray){
		
		sort($digitarray);
		$table[implode('', $digitarray)] = $value;
		
	}

	$lineval = '';
	foreach($display['digits'] as $displaydigit){
		
		$temparr = str_split($displaydigit);
		sort($temparr);
		$lineval .= $table[implode('', $temparr)];
			
	}

	$sum += $lineval;

}

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");
print($sum);