<?
	if (!defined("VALID")) die;

	require_once("inc/mb_functions.php");

	if (!$loggedIn) {
		echo "<H2>Message Board</H2>\n";
		div_box("Information", "You must be logged in to use this feature.<BR><BR>\n"
			. "<A HREF=\"$urlPrefix/signup\">Signup</A> or <A HREF=\"$urlPrefix/login\">Login</A>");

	} else {

	switch ($func) {
		case "editcat":
			$id = intval($arg1);
			$form = new formInput;

			if (!$is_admin) jsRedir("$urlPrefix/messageboard");

			echo "<H2>Edit Category</H2>\n";

			$category = mb_get_category($id);

			$form->start("$urlPrefix/messageboard/editcated");
			$form->hidden("id", $id);

			echo "<TABLE WIDTH=\"90%\" BORDER=0 ALIGN=\"center\">\n";

			echo "<TR>";
				echo "<TD>Name</TD>";
				echo "<TD>"; $form->text("name", $category->name, 32); echo "</TD>";
			echo "</TR>\n";

			echo "<TR>";
				echo "<TD VALIGN=\"top\">Description</TD>";
				echo "<TD>"; $form->textarea("description", 10, 60, $category->description); echo "</TD>";
			echo "</TR>\n";


			echo "<TR><TD COLSPAN=2 ALIGN=\"center\">";
			$form->submit("Update");
			echo "</TD></TR>\n";

			echo "</TABLE>\n";
			$form->end();
			break;

		case "editcated":
			if (!$is_admin) jsRedir("$urlPrefix/messageboard");

			$id = intval($_POST['id']);
			$name = $dbi->db->escape_string($_POST['name']);
			$description = $dbi->db->escape_string($_POST['description']);

			$qry = "UPDATE {$dbTables['mb_categories']} SET description='$description', name='$name' WHERE id='$id'";
			$sql = $dbi->db->query($qry);

			unset($func);
			require("cat/$cat.cat.php");
			break;

		case "delcat":
			if (!isset($arg1)) jsRedir("$urlPrefix/messageboard");

			$id = intval($arg1);
			if (!$is_admin) jsRedir("$urlPrefix/messageboard");

			$nodes = mb_category_get_all_children($id);

			$c = count($nodes);
			for($i=0; $i<$c; $i++) {
				$x = $nodes[$i];

				/* delete the categories */
				$qry = "DELETE FROM {$dbTables['mb_categories']} WHERE id='$x'";
				$sql = $dbi->db->query($qry);

				/* delete the posts */
				$qry = "DELETE FROM {$dbTables['mb_posts']} WHERE category='$x'";
				$sql = $dbi->db->query($qry);
			}

			$qry = "DELETE FROM {$dbTables['mb_categories']} WHERE id='$id'";
			$sql = $dbi->db->query($qry);

			$qry = "DELETE FROM {$dbTables['mb_posts']} WHERE category='$id'";
			$sql = $dbi->db->query($qry);

			unset($func);
			require("cat/$cat.cat.php");
			break;

		case "new":
			$parent = intval($arg1);
			$form = new formInput;

			if (!$is_admin) jsRedir("$urlPrefix/messageboard/category/$parent");

			echo "<H2>New Category</H2>\n";

			$form->start("$urlPrefix/messageboard/newed");
			$form->hidden("parent", $parent);

			echo "<TABLE WIDTH=\"90%\" BORDER=0 ALIGN=\"center\">\n";

			echo "<TR>";
				echo "<TD>Name</TD>";
				echo "<TD>"; $form->text("name", "", 32); echo "</TD>";
			echo "</TR>\n";

			echo "<TR>";
				echo "<TD VALIGN=\"top\">Description</TD>";
				echo "<TD>"; $form->textarea("description", 10, 60); echo "</TD>";
			echo "</TR>\n";


			echo "<TR><TD COLSPAN=2 ALIGN=\"center\">";
			$form->submit("Create");
			echo "</TD></TR>\n";

			echo "</TABLE>\n";
			$form->end();
			break;

		case "newed":
			$parent = intval($_POST['parent']);
			$name = $dbi->db->escape_string($_POST['name']);
			$description = $dbi->db->escape_string($_POST['description']);

			if (!$is_admin) jsRedir("$urlPrefix/messageboard/category/$parent");

			$qry = "INSERT INTO {$dbTables['mb_categories']} ( name, description, parent ) VALUES ('$name', '$description', '$parent')";

			$sql = $dbi->db->query($qry);

			unset($func);
			require("cat/$cat.cat.php");

			break;

		case "edit":
			$category = intval($arg1);
			$id = intval($arg2);
			$post = mb_get_post($id);

			$form = new formInput;

			if (isset($post) && $_SESSION['s_id'] == $post->user_id) {

				echo "<H2>Edit Post</H2>\n";

				$form->start("$urlPrefix/messageboard/edited");
				$form->hidden("category", $category);
				$form->hidden("id", $id);

				echo "<TABLE WIDTH=\"90%\" BORDER=0 ALIGN=\"center\">\n";

				echo "<TR>\n";
					echo "<TD>Subject</TD>\n";
					echo "<TD>"; $form->text("subject", $post->subject, 64); echo "</TD>\n";
				echo "</TR>\n";

				echo "<TR><TD VALIGN=\"top\">Message</TD><TD>"; $form->textarea("message", 15, 70, $post->message); echo "</TD></TR>\n";

				echo "<TR><TD COLSPAN=2 ALIGN=\"center\"><BR><BR>\n";
				$form->submit("Update");
				echo "</TD></TR>\n";

				echo "</TABLE>\n";

				$form->end();
			}
			break;

		case "edited":
			$category = intval($_POST['category']);
			$id = intval($_POST['id']);

			$post = mb_get_post($id);

			$subject = $dbi->db->escape_string($_POST['subject']);
			$message = $dbi->db->escape_string($_POST['message']);

			if (isset($post) && $_SESSION['s_id'] == $post->user_id) {
				$qry = "UPDATE {$dbTables['mb_posts']} SET subject='$subject', message='$message' WHERE id='$id'";
				$sql = $dbi->db->query($qry);
			}

			$func = "category";
			$arg1 = $category;
			require("cat/$cat.cat.php");
			break;

		case "delete";
			$category = intval($arg1);
			$thread = intval($arg2);

			$post = mb_get_post($thread);

			if ($_SESSION['s_id'] == $post->user_id) {

				$children = mb_post_get_all_children($category, $thread);

				$qry = "DELETE FROM {$dbTables['mb_posts']} WHERE id='$thread'";
				$sql = $dbi->db->query($qry);

				if ($sql) {
					$qry = "UPDATE {$dbTables['mb_categories']} SET post_count = post_count - 1 WHERE id='$category'";
					$sql = $dbi->db->query($qry);
				}

				$c = count($children);
				for($i=0; $i<$c; $i++) {
					$thread_id = $children[$i];
					$qry = "UPDATE {$dbTables['mb_posts']} SET parent='$post->parent' WHERE id='$thread_id'";
					$sql = $dbi->db->query($qry);
				}
			}

			$func = "category";
			$arg1 = $category;
			require("cat/$cat.cat.php");
			break;

		case "post":
			echo "<H2>Post</H2>\n";
			$category = intval($arg1);
			$parent_thread = intval($arg2);
			$form = new formInput;

			unset($errorMsg);
			if ($error >= 2) {
				$error -= 2;
				$errorMsg[] = "- Please enter a message";
			}
			if ($error >= 1) {
				$error -= 1;
				$errorMsg[] = "- Please enter a subject";
			}


			if ($category > 0) {

				$post = mb_get_post($parent_thread);
				if (isset($post)) {
					$subject = "RE: " . htmlspecialchars($post->subject);
					$message = "$post->user wrote:\n" . htmlspecialchars($post->message) . "\n";
				}

				$form->start("$urlPrefix/messageboard/posted");
				$form->hidden("parent", $parent_thread);
				$form->hidden("category", $category);

				echo "<TABLE WIDTH=\"90%\" BORDER=0 ALIGN=\"center\">\n";

				if (count($errorMsg)) {
					echo "<TR><TD COLSPAN=2 ALIGN=\"center\">\n";
					div_box("Message Board Post", join("<BR>", $errorMsg));
					echo "</TR>\n";
				}

				echo "<TR>\n";
					echo "<TD>Subject</TD>\n";
					echo "<TD>"; $form->text("subject", $subject, 64); echo "</TD>\n";
				echo "</TR>\n";

				echo "<TR><TD VALIGN=\"top\">Message</TD><TD>"; $form->textarea("message", 15, 70, $message); echo "</TD></TR>\n";

				echo "<TR><TD COLSPAN=2 ALIGN=\"center\"><BR><BR>\n";
				$form->submit("Post");
				echo "</TD></TR>\n";

				echo "</TABLE>\n";

				$form->end();
			} else {
				div_box("Information", "You are posting to an invalid message board, please try again.");
			}

			break;

		case "posted":
			$category = intval($_POST['category']);
			$parent = intval($_POST['parent']);
			$subject = $_POST['subject'];
			$message = $_POST['message'];

			if ($category > 0) {
				$error = 0;

				if (empty($subject)) $error += 1;
				if (empty($message)) $error += 2;

				if ($error) {
					$arg1 = $category;
					$arg2 = $parent;
					$func = "post";
					require("cat/$cat.cat.php");
				} else {
					/* increment post count */
					$qry = "UPDATE {$dbTables['mb_categories']} SET post_count = post_count + 1 WHERE id='$category'";
					$sql = $dbi->db->query($qry);


					$ip = $_SERVER['REMOTE_ADDR'];
					$user = $_SESSION['s_id'];
					$subject_e = $dbi->db->escape_string($subject);
					$message_e = $dbi->db->escape_string($message);

					$qry = "INSERT INTO {$dbTables['mb_posts']} ( category, ip, subject, message, user, parent ) "
						. " VALUES ( '$category', '$ip', '$subject_e', '$message_e', '$user', '$parent')";

					$sql = $dbi->db->query($qry);
					if (!$sql) {
						div_box("Critical Error", "There was a problem storing your post. Please contact the administrator!");
					}
				}
			}

			$func = "category";
			$arg1 = $category;

			require("cat/$cat.cat.php");

			break;

		case "category":
			$cat_id = intval($arg1);

			$parent = mb_category_get_parent($cat_id);
			echo "<H2>" . (empty($parent->name) ? "Message Board" : $parent->name) . "</H2>\n";

			$children = mb_categories_get_children($cat_id);
			if (isset($children)) {
				echo "<B>Sub-Categories</B><BR>\n";
				foreach($children as $id => $category) {
					echo "&nbsp; <A HREF=\"$urlPrefix/messageboard/category/$category->id\">" . stripslashes($category->name) . "</A> ($category->post_count posts)<BR>\n";
				}
			}

			echo "<BR>\n";
			echo "<LI><A HREF=\"$urlPrefix/messageboard/post/$cat_id/0\">New Thread</A></LI>\n";
			if ($is_admin) echo "<LI><A HREF=\"$urlPrefix/messageboard/new/$cat_id\">New Category</A></LI>\n";

			echo "<BR>\n";

			$total = display_mb_posts($cat_id, 0, 20);

			if ($total == 0) {
				div_box("Information", "There are not any posts in this category");
			}
			break;

		default:
			echo "<H2>Message Board</H2>\n";

			if ($is_admin) echo "<LI><A HREF=\"$urlPrefix/messageboard/new/0\">New Category</A></LI>\n";

			display_mb_categories();

			break;
	}
	}
?>
