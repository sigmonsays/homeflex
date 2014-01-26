<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "submit":
			$error = 0;

			$bugPriority = $_POST['bugPriority'];
			$bugSubject = $_POST['bugSubject'];
			$bugDescription = $_POST['bugDescription'];
			$bugContact = $_POST['bugContact'];

			if (empty($bugDescription)) $error += 1;
			if (empty($bugSubject)) $error += 2;
			if ($error) {
				$func = "report";
				require("cat/$cat.cat.php");
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
				$userid = ($loggedIn) ? $_SESSION['s_id'] : 0;
				$bugDescription = $dbi->db->escape_string($bugDescription);
				$bugContact = $dbi->db->escape_string($bugContact);
				$bugSubject = $dbi->db->escape_string($bugSubject);

				$sql = $dbi->db->query(
					"INSERT INTO {$dbTables['bugs']} VALUES(NULL, NULL, '$ip', '$bugPriority', '$bugSubject', "
					. "'$bugDescription', '$bugContact', '$userid', 'OPEN')");
				if ($sql) {
					?>
					<H2>Bug Submission Complete</H2>
					<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
					<TR><TD>
						Thank you for taking the time to submit a bug.<P>
						If you know the problem and would like to submit a patch, you can
						<A HREF="mailto:<?= $contact['email'] ?>">e-mail</A> the site admin.
						<P>
						<A HREF="<?= "$urlPrefix/" ?>"><< Homepage</A>
					</TD></TR>
					</TABLE>
					<?
				} else {
						echo "There was an error adding this entry to the database.<BR>";
						echo "I would submit it as a bug (via e-mail), even though it's possible<BR>";
						echo "We're having trouble with our database server. Thank You.<P>";
						$func = "report";
						require("cat/$cat.cat.php");
				}
			}
			break;

		case "report":
			$bugDescription .= htmlspecialchars(base64_decode($arg1));
		default:
			$form = new formInput;
			?>
			<H2>Report Bug</H2>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/bug/submit" ?>">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<?
				$errorMsg = array();
				if ($error >= 2) {
					$error -= 2;
					$errorMsg[] = "Subject is empty!";
				}
				if ($error >= 1) {
					$error -= 1;
					$errorMsg[] = "Description is empty";
				}

				$c = count($errorMsg);
				if ($c) {
					echo "<TR><TD COLSPAN=2><B>The following errors occurred</B><BR>\n";
					for($i=0; $i<$c; $i++)
						echo "<LI><FONT COLOR=\"red\">$errorMsg[$i]</FONT></LI>\n";
					echo "</TD></TR>\n";
				}

			?>
			<TR>
				<TD>Priority</TD>
				<TD><? $form->select("bugPriority", range(0, 10), $_POST['bugPriority']) ?></TD>
			</TR>
			<TR>
				<TD>Subject</TD>
				<TD><? $form->text("bugSubject", $_POST['bugSubject']) ?></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Description</TD>
				<TD><? $form->textarea("bugDescription", 10, 50, $_POST['bugDescription']) ?></TD>
			</TR>
			<TR>
				<TD>Contact <SMALL><I>(Optional)</I></SMALL></TD>
				<TD><? $form->text("bugContact", $_POST['bugContact']) ?></TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Submit Bug"></TD></TR>

			</TABLE>
			</FORM>
			<?
			break;
	}
?>
