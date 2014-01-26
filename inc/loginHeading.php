<?
	if (!defined("VALID")) die;
?>
&nbsp; <?
	if (empty($_SESSION['s_id'])) {
		echo "<A HREF=\"$urlPrefix/guest\">Preferences</A> &nbsp; | &nbsp; ";
		echo "<A HREF=\"$urlPrefix/signup\">Signup</A> &nbsp; | &nbsp; ";
		echo " <A HREF=\"$urlPrefix/login\">Login</A>";
	} else {
		echo "<A HREF=\"$urlPrefix/preferences\">Preferences</A> &nbsp; | &nbsp; ";
		echo "<A HREF=\"$urlPrefix/logout/?nocontent=1\">Logout</A>";
	}
?> &nbsp;
