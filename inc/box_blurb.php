<?
	$dbi->db->query("SELECT * FROM {$dbTables['blurb_box']} ORDER by `when` DESC LIMIT 5");

	if ($dbi->db->num_rows()) {
		while ($obj = $dbi->db->fetch_object()) {
			echo "<DIV CLASS=\"" . ( ($c = !$c) ? "row1" : "row2" ) . "\" STYLE=\"border: 1px solid black; padding: 0.3em; margin-bottom: 0.5em;\">\n";
			echo "<SMALL>" . formatTime($obj->when) . "</SMALL> - \n";

			echo "<B>$obj->subject</B><BR>\n";
			echo "$obj->message";
			echo "<BR>\n";

			if (!empty($obj->link)) {
				echo "<A HREF=\"$obj->link\">Link</A>\n";
			}
			echo "</DIV>\n";
		}
	}

?>
