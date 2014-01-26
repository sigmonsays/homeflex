<?
	if (!defined("VALID")) die;

	$includeLevel++;
	switch ($func) {

		default:
			$adminSection = $_POST['adminSection'];
			if (!isset($func)) $func = "news";
			if (!empty($adminSection)) $func = $adminSection;

			if ($includeLevel == 1 && !$nocontent) {

				$dbi->db->query("SELECT * FROM {$dbTables['sections']} WHERE active='1' AND admin='1' ORDER BY name ASC");
				if ($dbi->db->num_rows()) {
					?>
					<CENTER>
					<SCRIPT LANGUAGE="javascript1.1">
						function adminFormSubmit(f) {
							var s;
							s = f.adminSection.value;
							if (s.substring(0, 4) == "url:") {
								document.location = s.substring(4, s.length);
							} else {
								f.submit();
							}
						}
					</SCRIPT>
					<FORM NAME="adminForm" METHOD="post" ACTION="<?= "$urlPrefix/admin" ?>" OnChange="adminFormSubmit(this);">
					<SELECT NAME="adminSection">
					<?
					while ($section = $dbi->db->fetch_object()) {
						$selected = ($func == $section->include) ? "SELECTED" : "";
						$oAction = (empty($section->include)) ? "url:$section->url" : $section->include;
						echo "<OPTION $selected VALUE=\"$oAction\">$section->name</OPTION>";
					}
					echo "</SELECT> &nbsp; <INPUT TYPE=\"submit\" VALUE=\"OK\"></FORM></CENTER>";
				} else {
					echo "<CENTER><FONT COLOR=\"red\">menu error!</FONT></CENTER><BR>";
				}
			}

			if (file_exists("cat/admin/$func.cat.php")) {
				require("cat/admin/$func.cat.php");
			}
			break;
	}
?>
