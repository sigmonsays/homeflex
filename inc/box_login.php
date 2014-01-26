<?
	if (!defined("VALID")) die;

        if ($loggedIn) {
		echo "Logged in as " . $_SESSION['s_user'];
		echo "<BR><BR><A HREF=\"$urlPrefix/logout/?nocontent=1\">Logout</A>";
	} else {
		?>
		<FORM METHOD="post" ACTION="<?= "$urlPrefix/login/submit" ?>">
		<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
		<TABLE WID"50%" BORDER=0 ALIGN="center">
		<?
			if ($func == "error") echo "<TR><TD COLSPAN=2 ALIGN=\"center\"><FONT COLOR=\"red\">Login Error!</FONT></TD></TR>";
		?>
		<TR><TD><INPUT TYPE="text" NAME="userName" SIZE=12></TD></TR>
		<TR><TD><INPUT TYPE="password" NAME="userPass" SIZE=12></TD></TR>
		<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Login"></TD></TR>
		</TABLE>
		</FORM>
		<?
        }
?>
