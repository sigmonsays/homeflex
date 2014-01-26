<?

	switch ($func) {

		case "archive":
			
			$form = new sqlForm($dbi, $dbTables['blog'], DB_DATABASE);
			$form->urlPrefix = "$urlPrefix/blog/archive";
			$form->uniqueField = "id";
			$form->title = "Blog Archive";

			$form->setFieldType("when", "timestamp");
			$form->setFieldType("text", "textarea", 30, 70);
			$form->setFieldType("subject", "text", 50);

			$form->sqlOrderBy = "ORDER BY `when` DESC";

			$form->setFieldFunction("when", "timestamp", "F d Y");

			$fields = "subject,text";

			$form->mode = "list";

			$form->linkField("subject", "$urlPrefix/blog/id/\$id");

			$form->displayForm("subject,when", intval($_GET['start']), intval($_GET['offset']));
			break;

		case "id":
			?>
			<SCRIPT LANGUAGE="javascript">
			function launchComment(x) {
					window.open("<?= "$urlPrefix/blog/comments/" ?>" + x + "?nocontent=1", "blog_comment", 
					"toolbar=no,menubar=no,status=no,scrollbars=no,resizable=yes,width=690,height=330");
			}
			</SCRIPT>
			<?

			echo '<H2>Blog #' . str_pad($arg1, 4, "0", STR_PAD_LEFT) . "</H2>";

			echo "<A HREF=\"$urlPrefix/blog\"><< Back</A><BR><BR>";
			$qry = "SELECT * FROM {$dbTables['blog']} WHERE id='$arg1'";
			$sql = $dbi->db->query($qry);

			if ($sql && $dbi->db->num_rows($sql)) {

				$obj = $dbi->db->fetch_object($sql);

				echo '<TABLE WIDTH="100%">';
				echo '<TR>';
				echo "<TD><H3>$obj->subject</H3></TD>";
				echo '<TD ALIGN="right"><B>' . formatTime($obj->when) . '</B></TD>';
				echo '</TR>';

				echo '<TR>';
				echo '<TD COLSPAN=2 STYLE="border-bottom: 1px solid black;">';
				echo '<BR>' . nl2br(htmlspecialchars($obj->text));

				echo '<BR><BR>';
				echo "<INPUT TYPE=\"button\" OnClick=\"launchComment($obj->id);\" VALUE=\"Post Comment\">";

				echo '<BR><BR></TD></TR>';
				echo '</TABLE>';
				echo "<BR><BR>";

				$qry = "SELECT * FROM `{$dbTables['blog_comments']}` WHERE blogid='$obj->id'";
				$sql = $dbi->db->query($qry);

				if ($sql && $dbi->db->num_rows($sql)) {
					while ($obj = $dbi->db->fetch_object($sql)) {
						echo "<B>On " . formatTime($obj->when) . " " . (empty($obj->from) ? "<I>Anonymous</I>" : htmlspecialchars($obj->from))  . " said:</B><BR>";
						echo "<FONT SIZE=+1>" . htmlspecialchars($obj->subject) . "</FONT><BR><BR>";
						if (empty($obj->text))
							echo "<I>Nothing!</I>\n";
						else
							echo nl2br(stripslashes($obj->text)) . "<BR><BR><BR>\n";
					}
				}

			}
			break;

		case "comments":
			require_once("class/sqlForm.class.inc");

		        $form = new sqlForm($dbi, $dbTables['blog_comments'], DB_DATABASE);
		        $form->urlPrefix = "$urlPrefix/blog/comments";
			$form->urlVars = "blogid=$arg1";
		        $form->uniqueField = "id";

		        $form->setFieldType("when", "timestamp");
		        $form->setFieldType("text", "textarea", 10, 70);
		        $form->setFieldType("subject", "text", 50);

		        $form->sqlOrderBy = "ORDER BY `when` DESC";

		        $form->setFieldFunction("when", "timestamp", "F d Y");

			$form->postVariable("blogid", $arg1);

		        $fields = "subject,text";
			$form->mode = "add";

			if ($_GET['action'] == "addSubmit") {
				$form->addFormSubmit("from,subject,text,blogid");
				?>
				<SCRIPT LANGUAGE="javascript">
					window.close();
				</SCRIPT>
				<?
			} else {
				$form->addForm("from,subject,text");
			}
			break;

		default:
			echo '<H2>Latest 10 Blogs</H2>';

			echo "<A HREF=\"$urlPrefix/blog/archive\">Archives...</A><BR><BR>";
			
			$qry = "SELECT * FROM {$dbTables['blog']} ORDER BY `when` DESC LIMIT 10";
			$sql = $dbi->db->query($qry);
	
			echo '<TABLE WIDTH="100%">';
			echo '<TR>';
			echo '<TD COLSPAN=2 STYLE="border-bottom: 1px solid black;">';
			echo '<BR>';
			echo '<BR><BR></TD></TR>';
			echo '</TABLE><BR><BR>';

			while ($obj = $dbi->db->fetch_object($sql)) {

				echo '<TABLE WIDTH="100%">';
				echo '<TR>';
				echo "<TD><H3><A HREF=\"$urlPrefix/blog/id/$obj->id\">$obj->subject</A></H3></TD>";
				echo '<TD ALIGN="right"><B>' . formatTime($obj->when) . '</B></TD>';
				echo '</TR>';

				echo '<TR>';
				echo '<TD COLSPAN=2 STYLE="border-bottom: 1px solid black;">';
				echo '<BR>' . nl2br(htmlspecialchars($obj->text));
				echo '<BR><BR>';

				$sql2 = $dbi->db->query("SELECT COUNT(*) FROM `{$dbTables['blog_comments']}` WHERE blogid='$obj->id'");
				if ($sql2) {
					list($count) = $dbi->db->fetch_row($sql2);
					if ($count)
						echo "<A HREF=\"$urlPrefix/blog/id/$obj->id\">$count comments...</A>";
					else
						echo "<I>No Comments</I>";
				}

				echo '</TD></TR>';
				echo '</TABLE>';
				echo '<BR><BR>';
			}
			break;
	}
?>
