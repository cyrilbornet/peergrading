<?php

// ===== INCLUDED LIBRARIES ===============================================================================
date_default_timezone_set('Europe/Zurich');
session_start();
include('_db/_db.php');
include_once('_formutils.php');
#include_once('_jquery.php');


// ===== DOCUMENT INFORMATIONS ============================================================================
header('Vary: Accept');
header('Content-Type: text/html; charset=utf-8');
header('Last-modified: '.gmdate('D, d M Y H:i:s',mktime(date('H'), 0, 0)).' GMT');
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.'en'.'">';

// ===== DOCUMENT HEADERS =================================================================================
echo '<head>';
	// === Metas ===
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
	// === Global Style, used for common elements to all skins ===
	echo '<link rel="stylesheet" type="text/css" media="screen" href="/s/screen.css" />';
	if (isset($metas)) { print($metas); }
	if (is_array(@$GLOBALS['s'])) foreach ($GLOBALS['s'] as $s) {
		if (substr($s, 0, 1)=='<') echo $s;
		else echo '<link rel="stylesheet" media="all" type="text/css" href="/s/'.$s.'" />';
	}
	@$GLOBALS['js'][] = 'dh101.min.js';
	if (is_array($GLOBALS['js'])) foreach (@$GLOBALS['js'] as $js) {
		if (substr($js, 0, 1)=='<') echo $js;
		else echo '<script src="/js/'.$js.'" type="text/javascript"></script>';
	}
echo '</head>';


// ===== DOCUMENT BODY ====================================================================================
echo '<body>';

	echo '<header>';
		echo '<h1>DH101 - '.date('Y').' Fall Semester</h1>';
	echo '</header>';


	// Page content __________________________________________________
	echo '<section>';

?>