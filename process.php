<?php

/**
 * Apply a template to a range of indexes
 * example template: http://source.com/img_@@.gif
 * the @@ is the placeholder for the indexes
 * 
 * Optional: pass in a full url as a template, and the 
 * max of the range will become the placeholder
 * ex: range: 1-44
 *     template: image-name44.gif
 *
 */
function genLinks($template, $range) {
	$max = max($range);
	$len = strlen($max);
	// split on MAX or @@?
	$break = $max;
	if( strpos($template, '@@') > 0 ) {
		$break = '@@';
	}
	$result = explode($break, $template);
	if( count($result) !== 2 ) {
		return false;
	}
	list($pre, $post) = $result;
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

/**
 * @todo better handling of missing fields
 */
$required = ['template','pattern'];
foreach( $required as $req ) {
	if( !isset($_GET[$req]) ) {
		// a field is missing
		header('HTTP/ 400 Bad Request');
		die('error 400 -- bad request. missing parameter: ' . $req);
	}
}

// Process
// =======

$template = $_GET['template'];
$pattern  = $_GET['pattern'];

$range = buildRange($pattern);
$list  = genLinks($template, $range);
$success = ($list !== false);

// Output
// ======

header('Content-Type: application/json');

echo json_encode([
	'success' => $success,
	'range' => $range,
	'list' => $list,
], JSON_PRETTY_PRINT);


