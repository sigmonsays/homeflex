<?
	if (!defined("VALID")) die;
	/*
		This file formats the "Location". Since I use multiviews, and pass quite a bit of information
		through the URI, it gives them a good idea of where they are. Here you can override what is
		displayed, because it's nicer to see '/home/projects/php/man' instead of '/home/projects/id/135'


		accomplish this like so:
		take this function as a template:

		this function is called for each category (included file)
		if the function doesn't exist, a default url will be composed
		(sometimes quite ugly =) )

			--- SNIP ---
	function location_category($path, $def) {
		global $dbi, $urlPrefix;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
		}
		return 0;
	}
			--- SNIP ---

	*/

	function location_blog($path, $def) {
		global $urlPrefix;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/blog/id/[0-9]+", $path) ):
				$rv = "Blog #$arg1";
				return $rv;
				break;
		}
		return 0;
	}

	function location_cotd($path, $def) {
		global $urlPrefix;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/cotd", $path) ):
				$rv = "Linux command of the day";
				return $rv;
				break;
		}
		return 0;
	}

	function location_chat($path, $def) {
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/chat/id/", $path) ):
				$rv .= date("F d, Y");
				return $rv;
				break;
		}
		return 0;
	}


	function location_bug($path, $def) {
		global $urlPrefix;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/bug/report", $path) ):
				$rv .= _url(strstr("/", $def), "Report");
				return $rv;
				break;
		}
		return 0;
	}

	function location_movies($path, $def) {
		global $dbi, $urlPrefix, $dbTables;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/movies/id/[0-9]+", $path) ):
				$dbi->db->query("SELECT title FROM {$dbTables['movies']} WHERE id='$arg1'");
				if ($dbi->db->num_rows()) {
					list($title) = $dbi->db->fetch_row();
					$rv .= _url("$urlPrefix/movies/id/$arg1", $title);
					return $rv;
				}
				break;

		}
		return 0;
	}


	function location_projects($path, $def) {
		global $dbi, $urlPrefix, $dbTables;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/projects/id/[0-9]+", $path) ):
				$dbi->db->query("SELECT {$dbTables['projects']}.name, {$dbTables['projects_cat']}.name, {$dbTables['projects_cat']}.id "
					. "FROM {$dbTables['projects']}, {$dbTables['projects_cat']} "
					. "WHERE {$dbTables['projects']}.categoryID = {$dbTables['projects_cat']}.id "
					. "AND {$dbTables['projects']}.id='$arg1'");

				if ($dbi->db->num_rows()) {
					list($name, $category, $categoryID) = $dbi->db->fetch_row();
					$rv .= _url("$urlPrefix/projects/$categoryID", $category) . " / ";
					$rv .= _url("$urlPrefix/projects/id/$arg1", $name);
					return $rv;
				}
				break;


			case ( ereg("/projects/[0-9]+", $path) ):
				$dbi->db->query("SELECT name FROM {$dbTables['projects_cat']} WHERE id='$func'");

				if ($dbi->db->num_rows()) {
					list($name) = $dbi->db->fetch_row();
					$rv .= _url("$urlPrefix/projects/$func", $name);
					return $rv;
				}
				break;
		}
		return 0;
	}


	function location_pictures($path, $def) {
		global $dbi, $urlPrefix, $dbTables;
		list(, $cat, $func, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7) = split("/", $path);
		switch (1) {
			case ( ereg("/pictures/id/[0-9]+", $path) ):
				$dbi->db->query("SELECT name FROM {$dbTables['pictureCategories']} WHERE id='$arg1'");
				if ($dbi->db->num_rows()) {
					list($name) = $dbi->db->fetch_row();
					$rv .= _url("$urlPrefix/pictures/id/$arg1", stripslashes($name));
					return $rv;
				}
				break;

			case ( ereg("/pictures/displayPicture/[0-9]+", $path) ):
				$dbi->db->query(
					"SELECT {$dbTables['pictures']}.name, {$dbTables['pictureCategories']}.name, {$dbTables['pictureCategories']}.id "
					. "FROM {$dbTables['pictures']}, {$dbTables['pictureCategories']} "
					. "WHERE {$dbTables['pictures']}.category = {$dbTables['pictureCategories']}.id "
					. "AND {$dbTables['pictures']}.id='$arg1'");

				if ($dbi->db->num_rows()) {
					list($name, $category, $categoryID) = $dbi->db->fetch_row();
					$rv .= _url("$urlPrefix/pictures/id/$categoryID", stripslashes($category)) . " / ";
					$rv .= _url("$urlPrefix/pictures/displayPicture/$arg1", stripslashes($name));
					return $rv;
				}
				break;
		}
		return "";
	}


	function echoLocation() {
		global $loggedIn, $urlPrefix, $path_info, $cat;

		echo " &nbsp; ";
		echo ($loggedIn)  ? "<A HREF=\"$urlPrefix/login/info\">" . $_SESSION['s_user'] . "</A>" : "<A HREF=\"$urlPrefix/guest\">nobody</A>";
                echo " &nbsp; / ";

		//this loads the default url to echo out
                $url = split("/", $path_info);
                $c = count($url);
		$defUrl = "<A HREF=\"/\">Home</A>";
		if (!empty($path_info) && $path_info != "/") $defUrl .= " / ";

                for($i=0; $i < $c; $i++) {
								$name = ucwords(htmlspecialchars($url[$i]));
                        if (!empty($url[$i])) { 
									$x = join('/', array_slice($url, 0, $i + 1));
									if ($i < ($c - 1)) {
										$defUrl .= "<A HREF=\"$urlPrefix" . $x . "\">" . $name . "</A> / ";
									} else {
										$defUrl .= $name;
									}
                        }

                }

		$func = "location_$cat";
		if (function_exists($func)) {
			$loc = $func($path_info, $defUrl);
			if (empty($loc)) {
				echo $defUrl;
			} else {
				echo _url("/", "Home");
				echo " / " . _url("$urlPrefix/$cat", ucwords($cat)) . " / ";
				echo $loc;
			}
		} else
			echo $defUrl;

		echo " > ";

		flush();
	}
?>
