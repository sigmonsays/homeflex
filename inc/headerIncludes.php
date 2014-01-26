<?
	if (!defined("VALID")) die;
	
	@$d = opendir("$localPath/headerIncludes");
	if ($d) {

		readdir($d);
		readdir($d);

		echo "<TABLE WIDTH=\"100%\" BORDER=0 ALIGN=\"center\">\n";
		while ($file = readdir($d)) {
				if (filetype("$localPath/headerIncludes/$file") == 'file' && !ereg("_", $file)) {
					echo "<TR><TD>";
					require("$localPath/headerIncludes/$file");
					echo "</TD></TR>\n";
					echo "<TR><TD>&nbsp;</TD></TR>\n";
				}
		}
		echo "</TABLE>\n";
		closedir($d);
	}
?>
