<?
	if (!defined("VALID")) die;

	if ($loggedIn) {
		$all_options = getOptions($_SESSION['s_id']);
		@extract($all_options, EXTR_PREFIX_ALL, "opt");


	} else {
		if (isset($_COOKIE['guestOptions'])) {
			$tmpOptions = unserialize(stripslashes($_COOKIE['guestOptions']));
			@extract($tmpOptions, EXTR_PREFIX_ALL, "opt");
		}
	}

	if (isset($opt_skin)) {
		$dbi->db->query("SELECT `directory` FROM {$dbTables['skins']} WHERE id='$opt_skin'");

		if ($dbi->db->num_rows()) {
			list($siteTheme) = $dbi->db->fetch_row();
		}
	}


?>
