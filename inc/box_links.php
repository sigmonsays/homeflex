<?

		if (!defined("VALID")) die;

		$Toolbar = new Toolbar;

		$dbi->db->query("SELECT url,title FROM {$dbTables['links']} WHERE active='1' ORDER BY priority DESC, title ASC");

		if ($count = $dbi->db->num_rows()) {
			$i = 0;
			$extra = "";

			$pid = 0;

			while (list($url, $title) = $dbi->db->fetch_row()) {
				$i++;
				if (!empty($theme_link2_last_tag) && $i == $count) $extra = $theme_link2_last_tag;

				$id = $Toolbar->link($pid, $title, $url);
				$Toolbar->link_properties($id, $theme_link2_prefix, $theme_link2_suffix, "", "", $theme_link2_class);
			}

			$Toolbar->display();
		} else
			echo "<I>No Links</I>";
?>
