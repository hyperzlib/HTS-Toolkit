<?php
function getArrayString($format, $array){
	$temp = [];
	foreach($array as $one){
		$temp[] = sprintf($format, $one);
	}
	return '{' . implode(',', $temp) . '}';
}

function generatePhones($prefix, $format, $map){
	$txt = '';
	foreach($map as $key => $one){
		$txt .= sprintf('QS "%s-Phone_%s" %s', $prefix, $key, getArrayString($format, $one)) . "\r\n";
	}
	return $txt;
}

function generatePhoneList($prefix, $format, $map){
	$txt = '';
	foreach($map as $one){
		$txt .= sprintf('QS "%s-Phone_%s" %s', $prefix, $one, getArrayString($format, [$one])) . "\r\n";
	}
	return $txt;
}

$phoneMap = [
	'Voiced_Sounds' => ['a', 'o', 'e', 'e2', 'i', 'u', 'v', 'i', 'i2', 'i3', 'er', 'ai', 'ei', 'ao', 'ou', 'ia', 'ie', 'ua', 'uo', 've', 'iao', 'iou', 'uai', 'uei', 'an', 'en', 'in', 'vn', 'ian', 'uan', 'uen', 'van', 'ang', 'eng', 'ing', 'ong', 'iang', 'iong', 'uang', 'ueng', 'b', 'd', 'g', 'm', 'n', 'l', 'j', 'zh', 'z', 'w', 'y', 'r'],
	'Unvoiced_Sounds' => ['p', 't', 'k', 'f', 'h', 'x', 'ch', 'sh', 'c', 's'],
	'Vowels' => ['a', 'o', 'e', 'e2', 'i', 'u', 'v', 'i', 'i2', 'i3', 'er', 'ai', 'ei', 'ao', 'ou', 'ia', 'ie', 'ua', 'uo', 've', 'iao', 'iou', 'uai', 'uei', 'an', 'en', 'in', 'vn', 'ian', 'uan', 'uen', 'van', 'ang', 'eng', 'ing', 'ong', 'iang', 'iong', 'uang', 'ueng'],
	'Voiced_Consonants' => ['b', 'd', 'g', 'm', 'n', 'l', 'j', 'zh', 'z', 'w', 'y', 'r'],
	'Unvoiced_Consonantss' => ['p', 't', 'k', 'f', 'h', 'x', 'ch', 'sh', 'c', 's'],
	'Plosive_Consonant' => ['b', 'd', 'p', 't', 'k'],
	'Spirant_Consonants' => ['x', 'ch', 'sh', 'c', 's'],
	'Vowel_Consonants' => ['w', 'y'],
	'Lateral_Consonants' => ['g', 'f', 'h'],
	'Semivowel_Consonants' => ['m', 'n', 'j', 'zh', 'z', 'r'],
	'Slient' => ['sil', 'pau'],
];
$genMap = ['a', 'o', 'e', 'e2', 'i', 'u', 'v', 'i', 'i2', 'i3', 'er', 'ai', 'ei', 'ao', 'ou', 'ia', 'ie', 'ua', 'uo', 've', 'iao', 'iou', 'uai', 'uei', 'an', 'en', 'in', 'vn', 'ian', 'uan', 'uen', 'van', 'ang', 'eng', 'ing', 'ong', 'iang', 'iong', 'uang', 'ueng', 'b', 'd', 'g', 'm', 'n', 'l', 'j', 'zh', 'z', 'w', 'y', 'r', 'p', 't', 'k', 'f', 'h', 'x', 'ch', 'sh', 'c', 's'];
$ret = '';
$ret .= generatePhones('LL', '%s^*', $phoneMap) . "\n";
$ret .= generatePhoneList('LL', '%s^*', $genMap) . "\n\n";
$ret .= generatePhones('L', '*^%s-*', $phoneMap) . "\n";
$ret .= generatePhoneList('L', '*^%s-*', $genMap) . "\n\n";
$ret .= generatePhones('C', '*-%s+*', $phoneMap) . "\n";
$ret .= generatePhoneList('C', '*-%s+*', $genMap) . "\n\n";
$ret .= generatePhones('R', '*+%s=*', $phoneMap) . "\n";
$ret .= generatePhoneList('R', '*+%s=*', $genMap) . "\n\n";
$ret .= generatePhones('RR', '*=%s_*', $phoneMap) . "\n";
$ret .= generatePhoneList('RR', '*=%s_*', $genMap);
file_put_contents('test_qst.hed', $ret);
