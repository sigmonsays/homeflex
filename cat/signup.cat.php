<?
	if (!defined("VALID")) die;

	switch ($func) {
		case "confirm":
			$user = $arg1;
			$hash = $arg2;
			$error = "<H2>Account Confirmation Error</H2>\n";

			if (empty($arg1) || empty($arg2)) {
				echo $error;
			} else {

				$qry = "SELECT id FROM {$dbTables['users']} WHERE user='" . $dbi->db->escape_string($user) . "' AND hash='" . $dbi->db->escape_string($hash) . "'";
				$sql = $dbi->db->query($qry);
				if ($sql && $dbi->db->num_rows($sql)) {
					list($id) = $dbi->db->fetch_row($sql);

					$qry = "UPDATE {$dbTables['users']} SET active='1' WHERE id='$id'";
					$sql = $dbi->db->query($qry);
					if ($sql) {
						?>
						<H2>Account confirmed</H2>

						Your account is now active!<BR><BR>

						<A HREF="/login">Login</A><BR><BR>
						<?
					} else {
						echo $error;
					}
				} else {
					echo $error;
				}
			}
			break;

		case "submit":
			$username = $_POST['username'];
			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];
			$website = $_POST['website'];
			$aim = $_POST['aim'];

			$error = 0;
			if (empty($username)) $error+=1;
			if (empty($password1) || empty($password2) || ($password1 != $password2)) $error+=2;
			if (empty($email)) $error+=4;

			$qry = "SELECT COUNT(*) FROM {$dbTables['users']} WHERE user='" . $dbi->db->escape_string($username) . "'";
			$sql = $dbi->db->query($qry);
			if ($sql && $dbi->db->num_rows($sql)) {
				list($count) = $dbi->db->fetch_row($sql);
				if ($count) $error += 8;
			}

			if ($error) {
				unset($func);
				require("cat/$cat.cat.php");
			} else {

				srand(microtime() * 1000000);
				$hash = "h";
				for($i=0; $i<3; $i++) {
					$v = rand(100, 1000000);
					$hash .= $v;
				}

				$qry = "INSERT INTO {$dbTables['users']} ( user, password, email, website, aim, level, hash)";
				$qry .= " VALUES ( '" . $dbi->db->escape_string($username) . "', ";
				$qry .= "PASSWORD('" . $dbi->db->escape_string($password1) . "'), ";
				$qry .= "'" . $dbi->db->escape_string($email) . "', ";
				$qry .= "'" . $dbi->db->escape_string($website) . "', ";
				$qry .= "'" . $dbi->db->escape_string($aim) . "', ";
				$qry .= "'1', ";
				$qry .= "'" . $dbi->db->escape_string($hash) . "')";

				$sql = $dbi->db->query($qry);

				if ($sql) {

					?>
					<H2>Account Successfully Created</H2>
					<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
					<TR><TD>
						You have successfully created your account.<BR><BR>

						In a short amount of time you will receive an e-mail to confirm your account at the address you specified. Simply follow the link
						within and your account will then be activated and ready to use.


						<BR><BR>
					</TD></TR>
					</TABLE>
					<?

					$body  = "To finish the sign-up process, simply click the <B>Confirm Account</B> link below<BR><BR>\n";
					$body .= "<A HREF=\"$urlPrefix/signup/confirm/$username/$hash\">Confirm Account</A><BR><BR>\n";
					$body .= SERVER_URL;

					mail_html($email, $contact['email'], "Your account at $siteTitle", $body);
				} else {
					?>
					<H2>Error Creating Account</H2>
					There was an error setting up your account, this is by no means a definite fault of your own but there is always a possibility.<BR>
					Please paste the contents of the white box (if any) into the report bug comments section to help me fix this promptly.
					<BR><BR>

					<DIV STYLE="border: 1px solid black; background-color: white; padding: 1em; margin: 2em;">
					<?
						if ($admin_ip || ($loggedIn && $level >= 3)) {
							echo "QRY: " . htmlspecialchars($qry) . "<BR>";
						}

						echo "<B>" . basename(__FILE__) . "</B> line at <B>" . __LINE__ . "</B><BR>\n";

						echo htmlspecialchars($dbi->db->error());
					?>
					</DIV>
					<BR><BR>

					Please submit this as a bug using the link at the bottom of the page if you are sure all the information you entered was correct.
					<BR>
					<?
				}
			}

			break;

		default:
			$username = $_POST['username'];
			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];
			$website = $_POST['website'];
			$aim = $_POST['aim'];
			$form = new formInput;

			echo '<H2>Create Account</H2>';
			if ($loggedIn)
				echo "<CENTER><B>Note:</B> You are logged in! You don't need to sign up again. You can if you really want.</CENTER><BR><BR>";
			?>
			<FORM METHOD="post" ACTION="<?= "$urlPrefix/signup/submit" ?>">
			<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
			<?
				unset($errorMsg);
				if ($error >= 8) {
					$error -= 8;
					$errorMsg[] = "That user name is already taken";
				}

				if ($error >= 4) {
					$error -= 4;
					$errorMsg[] = "E-Mail is empty";
				}
				if ($error >= 2) {
					$error -= 2;
					$errorMsg[] = "Passwords don't match or you forgot to fill them in";
				}
				if ($error >= 1) {
					$error -= 1;
					$errorMsg[] = "User is empty";
				}
				$c = count($errorMsg);
				if ($c) {
					echo "<TR><TD COLSPAN=2><B>Who taught you to fill in forms!</B><BR>";
					for($i=0; $i<$c; $i++)
						echo "<LI><FONT COLOR=\"red\">$errorMsg[$i]</FONT></LI>";
					echo "<BR><BR></TD></TR>\n";
				}
			?>
			<TR>
				<TD>User</TD>
				<TD><? $form->text("username", $username); ?></TD>
			</TR>
			<TR>
				<TD>Password</TD>
				<TD><? $form->password("password1"); ?></TD>
			</TR>
			<TR>
				<TD>Confirm Password</TD>
				<TD><? $form->password("password2"); ?></TD>
			</TR>
			<TR>
				<TD>E-Mail</TD>
				<TD><? $form->text("email", $email); ?></TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR>
				<TD>Website</TD>
				<TD><? $form->text("website", $website); ?></TD>
			</TR>
			<TR>
				<TD>AIM/ICQ</TD>
				<TD><? $form->text("aim", $aim); ?></TD>
			</TR>
			
			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Create Account"></TD></TR>

			</TABLE>
			</FORM>
			<?
			break;
	}
?>
