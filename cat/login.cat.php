<?
	if (!defined("VALID")) die;
	switch ($func) {
		case "submit":
			$userName = $_POST['userName'];
			$userPass = $_POST['userPass'];

			$sql = $dbi->db->query("SELECT id,user,email,level "
				. "FROM {$dbTables['users']} WHERE user='$userName' "
				. "AND password=PASSWORD('$userPass') AND active='1'");

			if ($dbi->db->num_rows($sql)) {
				list($id, $user, $email, $level) = $dbi->db->fetch_row($sql);
				session_name("loginID");
				session_start();
				$loginID = session_id();
				$_SESSION['s_id'] = $id;
				$_SESSION['s_email'] = $email;
				$_SESSION['s_user'] = $user;
				$_SESSION['s_level'] = $level;
				header("Location: $urlPrefix/");
			} else
				header("Location: $urlPrefix/login/error");
			break;

		case "info":
			if ($loggedIn) {
				?>
				<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
				<TR><TD COLSPAN=2><H2><?= $_SESSION['s_user'] ?>'s Information</H2></TD></TR>
				<TR>
					<TD>User ID</TD>
					<TD><?= $_SESSION['s_id'] ?></TD>
				</TR>
				<TR>
					<TD>Mail</TD>
					<TD><?= $_SESSION['s_email'] ?></TD>
				</TR>

				<TR>
					<TD>Level</TD>
					<TD><?= $_SESSION['s_level'] ?></TD>
				</TR>

				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2>
					<LI><A HREF="<?= "$urlPrefix/preferences" ?>">User Preferences</A></LI>
					<LI><A HREF="<?= "$urlPrefix/logout/?nocontent=1" ?>">Logout</A></LI>
				</TD></TR>
				</TABLE>
				<?
			} else {
				?>
				<TABLE WIDTH="50%" BORDER=0 ALIGN="center">
				<TR><TD COLSPAN=2>
					<H2>nobody's Information</H2>
					You're not logged in.
				</TD></TR>
				</TABLE>
				<?

			}
			break;
		default:
			if ($loggedIn) {
				echo "You are already logged in. <A HREF=\"$urlPrefix/logout/?nocontent=1\">Logout</A>.";
			} else {
				?>
				<H2>Login</H2>

				<FORM METHOD="post" ACTION="<?= "$urlPrefix/login/submit" ?>">
				<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
				<TABLE WID"50%" BORDER=0 ALIGN="center">
				<?
					if ($func == "error") echo "<TR><TD COLSPAN=2 ALIGN=\"center\"><FONT COLOR=\"red\">Username/password error!</FONT></TD></TR>";
				?>
				<TR>
					<TD>User</TD>
					<TD><INPUT TYPE="text" NAME="userName"></TD>
				</TR>
				<TR>
					<TD>Password</TD>
					<TD><INPUT TYPE="password" NAME="userPass"></TD>
				</TR>
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Login"></TD></TR>
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2><A HREF="<?= "$urlPrefix/signup" ?>">Create</A> an account here if you don't have one.</A></TD></TR>
				</TABLE>
				</FORM>
				<?
			}
			break;
	}
?>
