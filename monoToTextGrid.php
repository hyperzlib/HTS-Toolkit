<?php
function loadMono($content){
    $lines = explode("\n", $content);
    $ret = [];
    foreach($lines as $line){
        $line = str_replace("\r", '', $line);
        if(!empty($line)){
            $label = explode(' ', $line);
            $ret[] = [
                floatval($label[0]) / 10000000,
                floatval($label[1]) / 10000000,
                $label[2],
            ];
        }
    }
    return $ret;
}

function monoToTextGrid($labels){
    $ret = ['File type = "ooTextFile"', 'Object class = "TextGrid"', 0];
    $length = end($labels)[1];
    $ret[] = $length;
    $ret[] = '<exists>';
    $ret[] = 1;
    $ret[] = '"IntervalTier"';
    $ret[] = '"silences"';
    $ret[] = 0;
    $ret[] = $length;
    $ret[] = count($labels);
    foreach($labels as $label){
        $ret[] = $label[0];
        $ret[] = $label[1];
        $ret[] = '"' . $label[2] . '"';
    }
    return implode("\r\n", $ret);
}

if($argc == 3){
	@mkdir($argv[2], true);
	foreach(glob($argv[1]) as $file){
		file_put_contents($argv[2] . '/' . str_replace('.lab', '.TextGrid', basename($file)), monoToTextGrid(loadMono(file_get_contents($file))));
	}
} else {
	echo 'Use: php ' . $argv[0] . ' <mono file(s)> <output folder>' . "\r\n";
	echo 'E.g: php ' . $argv[0] . ' mono/*.lab textgrid';
}