<?
	if (!defined("VALID")) die;
	/* this file is included from functions.inc, it contains helper functions
	*/

	function load_toolbar() {
		global $dbi, $dbTables;
		global $admin_ip, $loggedIn, $level, $localPath, $urlPrefix;
		global $theme_link_prefix, $theme_link_suffix, $theme_link_class;
		global $cat, $func, $arg1;

		$dbi->db->query("SELECT * FROM {$dbTables['sections']} WHERE inToolbar='1' AND active='1' AND regular='1' ORDER BY name ASC");
		if ($count = $dbi->db->num_rows()) {

			$mainToolbar = new Toolbar();

			if ($admin_ip || ($loggedIn && $level > 2)) {
				if ($cat == "admin" && file_exists("$localPath/toolbars/admin.inc")) {
					require("$localPath/toolbars/admin.inc");
				} else {
					$id = $mainToolbar->link(0, "Admin", "$urlPrefix/admin");
					$mainToolbar->link_properties($id, $theme_link_prefix, $theme_link_suffix, "", "", $theme_link_class);
				}
			}


			while ($section = $dbi->db->fetch_object()) {

				$url = ((empty($section->url)) ? "$urlPrefix/$section->include" : $section->url);

				if ($section->include == $cat && file_exists("$localPath/toolbars/$cat.inc")) {
					require("$localPath/toolbars/$cat.inc");
				} else {
					$id = $mainToolbar->link(0, $section->name, $url);
					$mainToolbar->link_properties($id, $theme_link_prefix, $theme_link_suffix, "", "", $theme_link_class);

					if ($cat == $section->include) $mainToolbar->selected($id, 1);
				}

			}
		}

		return $mainToolbar;
		
	}
?>
