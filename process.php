<?php

function genLinks($template, $range) {
	$max = max($range);
	$len = strlen($max);
	// split on MAX or @@?
	$break = $max;
	if( strpos($template, '@@') > 0 ) {
		$break = '@@';
	}
	list($pre, $post) = explode($break, $template);
	$result = [];
	foreach($range as $key => $value ) {
		$valPad = str_pad($value, $len, '0', STR_PAD_LEFT);
		$result[] = $pre . $valPad . $post;
	}
	return $result;

}

function buildRange($pattern) {
	$result = [];
	$commas = explode(",", $pattern);
	foreach($commas as $section) {
		// just a number?
		if( is_numeric($section) ) {
			$result[] = (int) $section;
			continue;
		}
		// a range?
		if( strpos($section, '-') > 0 ) {
			$split = explode('-', $section);
			$start = (int) $split[0];
			$end = (int) $split[1];
			if( $start > $end ) {
				$start = (int) $split[1];
				$end = (int) $split[0];
			}
			for( $i=$start; $i<=$end; $i++ ) {
				$result[] = $i;
			}
			continue;
		}
		// just shove it in there
		$result[] = $section;
	}
	return $result;
}

$required = ['template','pattern'];
foreach( $required as $req ) {
	if( !isset($_GET[$req]) ) {
		// a field is missing
		//header('HTTP/ 400 Bad Request');
		die('error 400 -- bad request ' . $req);
	}
}

header('Content-Type: application/json');

$template = $_GET['template'];
$pattern  = $_GET['pattern'];

echo json_encode([
	'range' => buildRange($pattern),
	'list' => genLinks($template, buildRange($pattern)),
], JSON_PRETTY_PRINT);