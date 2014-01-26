<?
	if (!defined("VALID")) die;
?>
        &copy; Copyleft 2002 &nbsp; 
	- <A HREF="<?= "$urlPrefix/index/?index=1" ?>">Index</A> &nbsp; 
	- <A HREF="<?= "$urlPrefix/contact" ?>">Contact</A> &nbsp; 
	- <A HREF="<?= "$urlPrefix/source/cat/$cat" ?>">View Source</A> &nbsp; 
<?

	if ($cat != "bug") {
		$bugInfo = $_SERVER['PHP_SELF'] . "\n";
		$bugInfo .= ($loggedIn) ? "Logged in as " . $_SESSION['s_user'] : "Not logged in";
		echo "- <A HREF=\"$urlPrefix/bug/report/" . base64_encode($bugInfo) . "\">Report Bug</A>";
	}
?>
	- <A HREF="<?= $_SERVER['REQUEST_URI'] . "?nocontent=1" ?>">Plain</A> -
<?
	$time_end = getmicrotime();
	$time_diff = $time_end - $time_start;

	printf("\n<BR><CENTER>homeflex generated in %.2f seconds (%d queries, %d bad)</CENTER>\n", $time_diff,
		$dbi->good_query_count() + $dbi->bad_query_count(), $dbi->bad_query_count()
	);
?>
