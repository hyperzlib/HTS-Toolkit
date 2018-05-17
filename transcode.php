<?php
foreach(glob('wav/*.wav') as $file){
	$filename = str_replace('.wav', '', basename($file));
	$dst = 'raw/' . $filename . '.raw';
	echo $filename . "\r\n";
	system('ffmpeg -hide_banner -i "' . $file . '" -f s16le -ar 48000 -acodec pcm_s16le "' . $dst . '"');
}