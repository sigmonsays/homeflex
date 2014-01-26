<?
	if (!defined("VALID")) die;

	$prefs = $dbTables['preferences'];
	$users = $dbTables['users'];

	switch ($func) {
		default:
			echo "<H2>Profiles</H2>\n";

			echo "<LI><A HREF=\"$urlPrefix/profiles\">List Users</A></LI><BR>\n";

			$user = $dbi->db->escape_string($func);

			$qry = "SELECT $users.* FROM $users,$prefs WHERE $users.id = $prefs.userid"
			. " AND $prefs.variable='public_profile' AND $prefs.value='1' AND $users.user='$user'";

			$sql = $dbi->db->query($qry);

			if ($sql && $dbi->db->num_rows($sql)) {

				$obj = $dbi->db->fetch_object($sql);

				$content = "Aim: $obj->aim<BR>\n";
				$content .= "Email: <A HREF=\"mailto:$obj->email\">$obj->email</A><BR>\n";

				div_box($obj->user, $content);

			} else {
				echo $dbi->db->error();

				div_box("Unknown profile", "For a list of users go to the <A HREF=\"$urlPrefix/profiles\">User Index</A> page.");
			}

			break;
	
	}
?>
