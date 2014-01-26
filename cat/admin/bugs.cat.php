<?
	if (!defined("VALID")) die;

	switch ($arg1) {
		
		case "edit":
			$form = new formInput;

			$dbi->db->query("SELECT * FROM {$dbTables['bugs']} WHERE id='$arg2'");
			if ($dbi->db->num_rows()) {
				$bug = $dbi->db->fetch_assoc();
				extract($bug, EXTR_PREFIX_ALL, "bug");
				?>
				<H2>Edit Bug</H2>
				<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/bugs/editSubmit" ?>">
				<? $form->hidden("bug_id", $bug_id) ?>
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Date</TD>
					<TD><B><? $form->date("bug_when", $bug_when, "F d, Y") ?></B></TD>
				</TR>
				<TR>
					<TD>Contact</TD>
					<TD><B><?= (!empty($bug_contact)) ? "<A HREF=\"mailto:$bug_contact\">$bug_contact</A>" : "<I>None</I>"; ?></TD>
				</TR>
				<TR>
					<TD>IP</TD>
					<TD><B><? $form->text("bug_ip", $bug_ip, 17) ?></B></TD>
				</TR>
				<TR>
					<TD>User</TD>
					<TD><?= ($bug_userid) ? getUserNameFromID($bug_userid) : "<I>Not logged in</I>"; ?></TD>
				</TR>
				<TR>
					<TD>Status</TD>
					<TD><? $form->select("bug_status", $bugStatus, $bug_status) ?></TD>
				</TR>
				<TR>
					<TD>Priority</TD>
					<TD><? $form->select("bug_priority", range(0, 10), $bug_priority) ?></TD>
				</TR>
				<TR>
					<TD>Subject</TD>
					<TD><? $form->text("bug_subject", $bug_subject) ?></TD>
				</TR>
				<TR>
					<TD>Description</TD>
					<TD><? $form->textarea("bug_description", 10, 50, $bug_description) ?></TD>
				</TR>

				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
				</TABLE>
				</FORM>
				<?
			} else {
				unset($arg1);
				require("cat/$cat.cat.php");
			}
			break;

		case "editSubmit":
			$bug_id = $_POST['bug_id'];
			$bug_status = $_POST['bug_status'];
			$bug_priority = $_POST['bug_priority'];
			$bug_subject = $_POST['bug_subject'];
			$bug_description = $_POST['bug_description'];

			$bug_subject = $dbi->db->escape_string($bug_subject);
			$bug_description = $dbi->db->escape_string($bug_description);

			$dbi->db->query(
				"UPDATE {$dbTables['bugs']} SET status='{$bugStatus[$bug_status]}', priority='$bug_priority', subject='$bug_subject', description='$bug_description' "
				. "WHERE id='$bug_id'");
			if ($sql) {
				unset($arg1);
				require("cat/$cat.cat.php");
			} else {
				echo "There was an error with the database updating! I don't know what to do now =)<BR>";
				$arg1 = "edit";
				$arg2 = $bug_id;
				require("cat/$cat.cat.php");
			}
			break;

		default:
			$form = new formInput;

			$status = $_POST['status'];

			if (!isset($status)) $status = (isset($arg1)) ? strip_tags($arg1) : "0";
			$start = intval($arg2);
			$offset = 50;

			$statuses = array_merge(array('-1' => " [ All ] "), $bugStatus);

			echo "<H2>Bugs</H2>";
			?>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/admin/bugs" ?>">
				<? $form->select("status", $statuses, $_POST['status']) ?> &nbsp; <INPUT TYPE="submit" VALUE="OK">
			</FORM>
			<P>
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<?

			$where = ($status == -1) ? "" : "WHERE status='{$bugStatus[$status]}'";
			$sql = $dbi->db->query(
				"SELECT * FROM {$dbTables['bugs']} $where"
				. "ORDER BY `priority` DESC, `when` DESC LIMIT $start, $offset");
			$count = $dbi->db->num_rows();

			$dbi->db->query(
				"SELECT COUNT(*) FROM {$dbTables['bugs']} $where");
			if ($sqlCount) list($totalCount) = $dbi->db->fetch_row();

			if ($sql && $count) {
				$c = 0;
				$i = $start;
				?>
				<TR><TD COLSPAN=5><?= (($status == -1) ? "All bugs " : "$bugStatus[$status] bugs ") . ($start + 1) . " through " . ($start + $count) . ", $totalCount $bugStatus[$status] total" ?></TD></TR>
				<TR>
					<TD WIDTH=5>&nbsp;</TD>
					<TD><B>Subject</B></TD>
					<TD><B>Priority</B></TD>
					<TD><B>Date</B></TD>
					<TD>&nbsp;</TD>
				</TR>
				<?
				while ($bug = $dbi->db->fetch_object($sql)) {
					$c = !$c;
					$i++;
					$class = ($c) ? "row1" : "row2";
					echo "<TR CLASS=\"$class\">";
						echo "<TD WIDTH=5>$i</TD>";
						echo "<TD>";
							$s = substr($bug->subject, 0, 20);
							if ($status == -1) {
								if ($bug->status == "OPEN")
									echo "<B>$s</B>";
								elseif ($bug->status == "CLOSED")
									echo "<I>$s</I>";
								elseif ($bug->status == "READ")
									echo $s;
							} else
								echo $s;
						echo "</TD>";
						echo "<TD>$bug->priority</TD>";
						echo "<TD>" . formatTime($bug->when) . "</TD>";
						echo "<TD ALIGN=\"center\">";
							echo "<A HREF=\"$urlPrefix/admin/bugs/edit/$bug->id\">edit</A>";
						echo "</TD>";
					echo "</TR>\n";
				}

				echo "<TR><TD COLSPAN=5>&nbsp;</TD><TR><TD COLSPAN=5 ALIGN=\"right\">";
				$pstart = $start - $offset;
				$nstart = $start + $offset;
				if ($pstart >= 0)
					echo "<A HREF=\"$urlPrefix/admin/bugs/$bugStatus[$status]/$pstart\"><< Previous $offset</A>";
				else
					echo "<< Previous $offset";
				echo " &nbsp; ";
				if ($nstart <= $totalCount)
					echo "<A HREF=\"$urlPrefix/admin/bugs/$bugStatus[$status]/$nstart\">Next $offset >></A>";
				else
					echo "Next $offset >>";
				echo "</TD></TR>\n";

			} else
				echo "<TR><TD COLSPAN=5><I>None</I></TD></TR>\n";
			echo "</TABLE>\n";
			if ($status == -1) echo "<BR>Legend: <B>OPEN</B>, <I>CLOSED</I>, READ<BR><BR>";
			break;
	}
?>
