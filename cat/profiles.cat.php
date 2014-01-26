<?
	if (!defined("VALID")) die;

	switch ($func) {
		default:
			echo "<H2>Users</H2>\n";
			$prefs = $dbTables['preferences'];
			$users = $dbTables['users'];
                        
			$qry = "SELECT $prefs.*, $users.* FROM $prefs,$users WHERE $users.id = $prefs.userid "
						. "AND $prefs.variable='public_profile' AND $prefs.value='1' ORDER BY $users.user";
                        
			$sql = $dbi->db->query($qry);

			if ($sql && ($user_count = $dbi->db->num_rows($sql))) {
                        
				$sql2 = $dbi->db->query("SELECT COUNT(*) FROM $users");
				if ($sql2 && $dbi->db->num_rows($sql)) {
					list($count) = $dbi->db->fetch_row($sql2);
				}
                                
				if ($count - $user_count) {
					echo "<CENTER><B>" . ($count - $user_count) . "</B> have their profile marked private</CENTER><BR><BR>\n";
				}
                                
				while ($obj = $dbi->db->fetch_object($sql)) {

					$content = "Aim: $obj->aim<BR>\n";
					$content .= "Email: <A HREF=\"mailto:$obj->email\">$obj->email</A><BR>\n";
					div_box($obj->user, $content);
				}
			}
			break;
	}
?>
