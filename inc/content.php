<?
	if (!defined("VALID")) die;


	if (file_exists("$localPath/cat/$cat.cat.php")) {

		/* check if they can include it */
		$dbi->db->query("SELECT include,active,admin,regular FROM {$dbTables['sections']} WHERE include='$cat'");
		if ($dbi->db->num_rows()) {
			$include = $dbi->db->fetch_object();

			
			if ($include->active) {

				if ($include->regular || ($include->admin && $is_admin)) {
					require("$localPath/cat/" . $include->include . ".cat.php");
				} else {
					echo "<H2>access denied</H2>\n";
				}

			} else {
				echo "<H2>section disabled</H2>\n";
			}

		} else {

			/* if record is not found we should just include it anyways */
			require("$localPath/cat/$cat.cat.php");
		}
	} else {
		require("$localPath/cat/news.cat.php");
	}
?>
