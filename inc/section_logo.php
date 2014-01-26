<?
	$qry = "SELECT logo FROM {$dbTables['sections']} WHERE include='$cat'";

	$dbi->db->query($qry);
	if ($dbi->db->num_rows()) {
		list($logo) = $dbi->db->fetch_row();
		if (!empty($logo)) echo "<IMG SRC=\"$urlPrefix/homeflex/sectionLogos/$logo\">\n";
	}
?>
