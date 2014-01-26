<H2>Misc Docs</H2>
<?
	if (!defined("VALID")) die;

	$dir = opendir("$localPath/docs");
	readdir($dir);
	readdir($dir);

	echo "<UL>";
	while ($doc = readdir($dir)) {
		echo "<LI><A HREF=\"/homeflex/docs/$doc\">$doc</A></LI>";
	}
	echo "</UL>";

	closedir($dir);
?>
