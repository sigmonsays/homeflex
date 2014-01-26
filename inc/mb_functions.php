<?
	function mb_get_post($id) {
		global $dbi, $dbTables;

		$qry = "SELECT {$dbTables['mb_posts']}.*,{$dbTables['users']}.user,{$dbTables['users']}.id as user_id FROM {$dbTables['mb_posts']},{$dbTables['users']} WHERE "
			. "{$dbTables['users']}.id = {$dbTables['mb_posts']}.user AND {$dbTables['mb_posts']}.id='$id'";

		$dbi->db->query($qry);
		if ($dbi->db->num_rows()) {
			$obj = $dbi->db->fetch_object();
		}
		return $obj;
	}

	function mb_get_category($id) {
		global $dbi, $dbTables;

		$qry = "SELECT * FROM {$dbTables['mb_categories']} WHERE id='$id'";
		$sql = $dbi->db->query($qry);
		if ($dbi->db->num_rows()) {
			$obj = $dbi->db->fetch_object();
		}
		return $obj;
	}

	/* BEGIN array category recursive functions */
	function mb_category_get_all_children($id) {
		unset($rv);
		recurs_categories_array($id, &$rv);
		return $rv;
	}

	function recurs_categories_array($start, $array) {

		$children = mb_categories_get_children($start);

		if (isset($children)) {
		
			foreach($children as $id => $category) {
				$array[] = $category->id;
				recurs_categories_array($id, &$array);
			}
		}
	}

	/* END arary category recursive functions */

	/* BEGIN category recursive functions */

	function mb_category_get_parent($id) {
		global $dbi, $dbTables;

		$qry = "SELECT * FROM {$dbTables['mb_categories']} WHERE id='$id'";

		$dbi->db->query($qry);

		if ($dbi->db->num_rows()) {
			$obj = $dbi->db->fetch_object();
		}
		return $obj;
	}

	function mb_categories_get_children($id) {
		global $dbi, $dbTables;

		$qry = "SELECT * FROM {$dbTables['mb_categories']} WHERE parent='$id'";
		$dbi->db->query($qry);

		unset($rv);
		if ($dbi->db->num_rows()) {
			while ($obj = $dbi->db->fetch_object()) {
				$rv[$obj->id] = $obj;
			}
		}
		return $rv;
	}

	function recurs_display_mb_categories($start, $depth) {
		global $is_admin;

		$children = mb_categories_get_children($start);

		if (isset($children)) {
		
			$depth++;
			foreach($children as $id => $category) {

				$class = (($depth % 2) == 0) ? "row1" : "row2";

				$d = $depth + 1;
				if ($depth == 1) echo "<BR>\n";

				echo "<DIV CLASS=\"$class\" STYLE=\"border: 1px solid black; margin-bottom: 0.3em; padding: 0.3em; margin-left: $d" . "em; margin-right: $d" . "em\">\n";
				echo "<TABLE CELLSPACING=0 CELLPADDING=0 WIDTH=\"100%\">\n";
				echo "<TR><TD>\n";
				echo "<A HREF=\"$urlPrefix/messageboard/category/$category->id\">" . stripslashes($category->name) . "</A><BR>\n";
				echo htmlspecialchars(stripslashes($category->description));
				echo "</TD>";

				echo "<TD ALIGN=\"right\">\n";
				echo "$category->post_count posts";

				if ($is_admin) {
					echo "<BR>\n";
					echo "<A TITLE=\"Edit this category\" HREF=\"$urlPrefix/messageboard/editcat/$category->id\">Edit</A> &nbsp; ";
					echo "<A TITLE=\"Delete this category\" HREF=\"$urlPrefix/messageboard/delcat/$category->id\">Delete</A> &nbsp; ";
					echo "<A TITLE=\"New category here\" HREF=\"$urlPrefix/messageboard/new/$category->id\">New</A>";
				}

				echo "</TD></TR>\n";
				echo "</TABLE>\n";
				echo "</DIV>\n";


				recurs_display_mb_categories($id, $depth);
			}
		}
	}

	function display_mb_categories($start = 0) {
		$depth = 0;
		recurs_display_mb_categories($start, $depth);
	}


	/* END category recursive functions */


	/* END array post recurisve functions */

	function mb_post_get_all_children($cat, $id) {
		unset($rv);
		recurs_posts_array($cat, $id, &$rv);
		return $rv;
	}

	function recurs_posts_array($cat, $start, $array) {

		$children = mb_posts_get_children($cat, $start);

		if (isset($children)) {
		
			foreach($children as $id => $post) {
				$array[] = $post->id;
				recurs_posts_array($cat, $id, &$array);
			}
		}
	}

	/* END array post recurisve functions */

	/* BEGIN post recursive functions */

	function mb_posts_get_children($cat, $id) {
		global $dbi, $dbTables;

		$qry = "SELECT {$dbTables['mb_posts']}.*,{$dbTables['users']}.user, {$dbTables['users']}.id as user_id FROM {$dbTables['mb_posts']},{$dbTables['users']} WHERE "
			. "{$dbTables['users']}.id = {$dbTables['mb_posts']}.user AND parent='$id' AND category='$cat'";

		$dbi->db->query($qry);
		if ($dbi->db->num_rows()) {
			while ($obj = $dbi->db->fetch_object()) {
				$rv[$obj->id] = $obj;
			}
		}
		return $rv;
	}



	function recurs_display_mb_posts($cat, $start, $depth, $maxDepth, $total = 0) {

		$children = mb_posts_get_children($cat, $start);

		if (isset($children)) {
			$depth++;
			foreach($children as $id => $post) {

				$total++;
				$d = $depth + 1;
				echo "<DIV STYLE=\"margin-bottom: 0.3em; padding: 0.3em; margin-left: $d" . "em; margin-right: $d" . "em\">\n";
				echo "<B>" . htmlspecialchars(stripslashes($post->subject)) . "</B> - <I>" . formatTime($post->created) . "</I><BR>\n";
				echo "by <B>$post->user</B><BR>\n";
				echo nl2br(htmlspecialchars(stripslashes($post->message)));
				echo "<BR>";

				echo "<A HREF=\"$urlPrefix/messageboard/post/$cat/$post->id\">Reply</A> &nbsp; ";

				if ($_SESSION['s_id'] == $post->user_id) {
					echo "<A HREF=\"$urlPrefix/messageboard/delete/$cat/$post->id\">Delete</A> &nbsp; ";
					echo "<A HREF=\"$urlPrefix/messageboard/edit/$cat/$post->id\">Edit</A>";

				}
		
				echo "</DIV>\n";

				if ($depth > $maxDepth) break;

				recurs_display_mb_posts($cat, $id, $depth, $maxDepth, &$total);
			}
		}
	}

	function display_mb_posts($cat, $start, $maxDepth) {
		$depth = 0;
		$total = 0;
		recurs_display_mb_posts($cat, $start, $depth, $maxDepth, &$total);
		return $total;
	}

	/* END post resursive functions */

?>
