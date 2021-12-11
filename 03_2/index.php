<?php

$data = [];

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		$data[] = $line;
		
	}

	fclose($handle);
}

$odata = $data;

$pointlength = trim($odata[0]);

for($i = 0; $i <= strlen($pointlength); $i++){
	
	$ones = 0;
	$zeroes = 0;
	
	foreach ($odata as $opoint){
		if ($opoint[$i] == 0){
			$zeroes++;
		} else {
			$ones++;
		}
	}
	
	if ($zeroes > $ones){
		$badchar = '1';
	} else {
		$badchar = '0';
	}
	
	foreach($odata as $key => $opoint){
		if ($opoint[$i] == $badchar && count($odata) > 1){
			unset($odata[$key]);
		}
	}
	
}

$oval = bindec(array_values($odata)[0]);

$cdata = $data;

for($i = 0; $i <= strlen($pointlength); $i++){

	$ones = 0;
	$zeroes = 0;

	foreach ($cdata as $cpoint){
		if ($cpoint[$i] == 0){
			$zeroes++;
		} else {
			$ones++;
		}
	}

	if ($zeroes > $ones){
		$badchar = '0';
	} else {
		$badchar = '1';
	}

	foreach($cdata as $key => $cpoint){
		if ($cpoint[$i] == $badchar && count($cdata) > 1){
			unset($cdata[$key]);
		}
	}

}

$cval = bindec(array_values($cdata)[0]);

print($oval * $cval);
