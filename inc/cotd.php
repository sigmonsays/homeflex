<?
	if (!defined("VALID")) die;

	$d = date("z");

	$dbi->db->query("SELECT * FROM {$dbTables['cotd']} WHERE day='$d'");
	if ($dbi->db->num_rows()) {

		$cotd = $dbi->db->fetch_object();
		?>
		<H2>Linux Command of the Day</H2>
		<B>Description</B><BR>
		<?= htmlspecialchars($cotd->description); ?>
		<P>
		<B>Command</B><BR>
		<?
		echo htmlspecialchars($cotd->command);
	} else {
		echo "<A HREF=\"$urlPrefix/cotd\">Submit Command of the day</A>";
	}
?>
