<?php
function loadTextGrid($content){
	$lines = explode("\r\n", $content);
	$isStart = false;
	$nowInterval = 0;
	$intervals = [];
	$textMap = [
		'xmin' => 0,
		'xmax' => 1,
		'text' => 2,
	];
	foreach($lines as $line){
		$line = trim($line);
		if(!empty($line)){
			if(substr($line, 0, strlen('intervals [')) == 'intervals ['){
				if($isStart != true){
					$isStart = true;
				}
				$nowInterval = intval(str_replace(['intervals [', ']:'], ['', ''], $line));
			} elseif($isStart){
				$t = explode('=', $line);
				$t[0] = trim($t[0]);
				$t[1] = str_replace('"', '', trim($t[1]));
				if(isset($textMap[$t[0]])){
					$intervals[$nowInterval][$textMap[$t[0]]] = $t[1];
				}
			}
		}
	}
	return $intervals;
}

function textGridToMono($intervals){
	$mono = '';
	foreach($intervals as $one){
		$mono .= round($one[0] * 1000000) . ' ' . round($one[1] * 1000000) . ' ' . $one[2] . "\r\n";
	}
	return $mono;
}
if($argc == 3){
	@mkdir($argv[2], true);
	foreach(glob($argv[1]) as $file){
		file_put_contents($argv[2] . '/' . str_replace('.TextGrid', '.lab', basename($file)), textGridToMono(loadTextGrid(file_get_contents($file))));
	}
} else {
	echo 'Use: php ' . $argv[0] . ' <textgrid file(s)> <output folder>' . "\r\n";
	echo 'E.g: php ' . $argv[0] . ' textgrid/*.TextGrid mono';
}