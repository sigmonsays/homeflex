<?
/*
	mvHandler.php
	replaces $cat with it's own symlink name
*/
	chdir("./homeflex");
	require_once("/etc/homeflex.php");
	require_once("$localPath/inc/functions.php");

	if (!defined("VALID")) die("Check your configuration");

	$time_start = getmicrotime();

	//override $cat variable
	list(, $cat) = split("/", strip_tags($_SERVER['PHP_SELF']));
	$cat = str_replace(".php", "", $cat);

	$ar = explode("/", strip_tags($_SERVER['PHP_SELF']));
	$c = count($ar);
	for($i=0; $i<$c; $i++) {
		$ar[$i] = str_replace(".php", "", $ar[$i]);
	}

	@list(, , $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8) = $ar;

	if (($cat == 'index') && (intval(@$_GET['index']) == 0)) {
		$cat = "news";
		$ar[1] = $cat;
	}

	$path_info = implode("/", $ar);

	if (!intval(@$_GET['cache']) && !headers_sent()) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	require("main.php");
?>
