<H1>Index</H1>
	<?
		$dbi->db->query("SELECT * FROM {$dbTables['sections']} WHERE active='1' AND regular='1' ORDER BY name ASC");
		if ($count = $dbi->db->num_rows()) {
			$i = 0;
			$extra = "";
			while ($section = $dbi->db->fetch_object($sql)) {

				$i++;
				$url = (empty($section->url)) ? "$urlPrefix/$section->include" : $section->url;
				$link = "<LI><A HREF=\"$url\">$section->name</A>";

				if ($section->admin) {
					if ($admin) {
						echo $link;
						echo "<BR>$section->description<BR><BR></LI>";
					}
				} else {
					echo $link;
					echo "<BR>$section->description<BR><BR></LI>";
				}


			}
		} else
			echo "<I>No Links</I>";
	?>
