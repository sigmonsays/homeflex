<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "results":
			$qry = $_POST['qry'];

			if (empty($qry)) {
				unset($func);
				require("$localPath/cat/$cat.cat.php");
			} else {
				$sqlString = "SELECT name,include,url,description FROM {$dbTables['sections']} WHERE admin='0' AND description LIKE '%$qry%'";
				$sql = $dbi->db->query($sqlString);

				echo "<H2>Search Results</H2>";
				echo "<B>Query:</B> " . htmlspecialchars(stripslashes($qry)) . "<BR>";
				echo "Found " . $dbi->db->num_rows($sql) . " pages matching your query.<P>";

				if ($sql && $dbi->db->num_rows($sql)) {
					echo "<UL>";
					while ($section = $dbi->db->fetch_object($sql)) {
						$url = ((empty($section->url)) ? "$urlPrefix/$section->include" : $section->url);
						$link = "<A HREF=\"$url\">$section->name</A>";

						echo "<LI>$link<BR>$section->description</LI><BR>";
					}
					echo "</UL>";
				} else {
					echo '<I>Nothing appropriate found!</I>';
				}
				
			}

			break;

		default:
			echo '<H2>Search</H2>';
			require("$localPath/inc/box_search.php");
			echo '<P>';
			break;
	}
?>
