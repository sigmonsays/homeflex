<?
	/* 	/show/me/the handler
	*/

	define("VALID", 1);
	define("MV_PATH_OFFSET", 1);
	define("MV_PATH_DISPLAY_OFFSET", 2);

	chdir("./homeflex");
	require_once("/etc/homeflex.php");
	require_once("$localPath/inc/functions.php");

	if (!defined("VALID")) die("Check your config\n");

	$time_start = getmicrotime();

	//override $cat variable
	$ar = explode("/", strip_tags($_SERVER['PHP_SELF']));
	$c = count($ar);

	$ar[0] = str_replace(".php", "", $ar[0]);

	$cat = $ar[MV_PATH_OFFSET + 1];

	list($func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8) = array_slice($ar, MV_PATH_OFFSET + 2);

	$ar = array_slice($ar, MV_PATH_DISPLAY_OFFSET);

	if (($cat == 'index') && (intval($_GET['index']) == 0)) {
		$cat = 'news';
		$ar[0] = 'news';
	}

	$path_info = "/" . implode("/", $ar);

	if (!$cache && !headers_sent()) {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}

	require("main.php");
?>
