<?php
print('<pre>');
$start = microtime(true);

$handle = fopen('input.txt', 'r');
if ($handle) {
	
	while (($line = fgets($handle)) !== false) {
		
		if (!empty(trim($line))){
			$data = trim($line);
		}

	}

	fclose($handle);

}

$binary = str_replace(
		['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F'],
		['0000','0001','0010','0011','0100','0101','0110','0111','1000','1001','1010','1011','1100','1101','1110','1111'],
		$data);


$version_sum = 0;

function parse_packet($binary) {
	
	$packet = ['data' => []];
	
	if (!str_replace('0', '', $binary)){
		return [false, ''];
	}
	
	if(!empty($binary)){
		list($version, $binary) = preg_split('/(?<=.{3})/', $binary, 2);
		$packet['version'] = bindec($version);
		$GLOBALS['version_sum'] += $packet['version'];
	}

	if(!empty($binary)){
		list($type, $binary) = preg_split('/(?<=.{3})/', $binary, 2);
		$packet['type'] = bindec($type);
		
		if ($packet['type'] == 4){
			
			$literal = '';
			$more = true;
			while($more){
				list($byte, $binary) = preg_split('/(?<=.{5})/', $binary, 2);
				list($more, $number) = preg_split('/(?<=.{1})/', $byte, 2);
				$literal .= $number;
			}
			
			$packet['data'][] = ['number' => bindec($literal)];
			
		} else {
			
			list($lengthtype, $binary) = preg_split('/(?<=.{1})/', $binary, 2);
			$packet['lengthtype'] = bindec($lengthtype);
			
			list($subpacketcount, $binary) = preg_split('/(?<=.{'.($lengthtype ? 11 : 15).'})/', $binary, 2);
			$packet['subpacketcount'] = bindec($subpacketcount);
			
			if ($packet['lengthtype'] == 0){
				
				list($subpackets, $binary) = preg_split('/(?<=.{'.$packet['subpacketcount'].'})/', $binary, 2);
				
				while(strlen($subpackets)){
					list($subpacket, $subpackets) = parse_packet($subpackets);
					if ($subpacket !== false){
						$packet['data'][] = ['subpacket' => $subpacket];
					}
				}
				
			} else {
				
				for($i = 0; $i < $packet['subpacketcount']; $i++){
					list($subpacket, $binary) = parse_packet($binary);
					if ($subpacket !== false){
						$packet['data'][] = ['subpacket' => $subpacket];
					}
				}
				
			}
			
		}
	}
	
	return [$packet, $binary];

}

list($packet, $binary) = parse_packet($binary);

print(round((microtime(true) - $start)*1000, 1) . "ms\n\n");

print($version_sum);
