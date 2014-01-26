<?
	if (!defined("VALID")) die;

	if ($loggedIn) {
		$cat = "preferences";
		require("cat/$cat.cat.php");
	} else {

	switch ($func) {
		case "submit":
			$options = $_POST['options'];

			if (headers_sent()) {
				unset($func);
				require("cat/$cat.cat.php");
			} else {
				$guestOptions = serialize($options);

				setcookie("guestOptions", $guestOptions, time() + COOKIE_LIFE, COOKIE_PATH, COOKIE_DOMAIN);

				header("Location: $urlPrefix/guest/saved");
			}
			break;

		case "set":
			$var = ereg_replace("[^a-z]", "", $arg1);
			$value = $arg2;

			if (!headers_sent()) {

				$newGuestOptions = unserialize(stripslashes($_COOKIE['guestOptions']));

				$newGuestOptions[$var] = $value;

				$guestOptions = serialize($newGuestOptions);

				setcookie("guestOptions", $guestOptions, time() + COOKIE_LIFE, COOKIE_PATH, COOKIE_DOMAIN);

				header("Location: $urlPrefix/guest/saved");
			} else
				jsRedir("$urlPrefix/guest/set/" . urlencode($var) . "/" . urlencode($value) . "/?nocontent=1");
			break;

		default:
			$form = new formInput;

			$options = unserialize(stripslashes($_COOKIE['guestOptions']));

			//this is kinda of sloppy =)
			@extract($options);

			echo '<H2>Guest Options</H2>';
			if ($func == "saved") echo "<CENTER>Guest Settings updated.</CENTER><BR><BR>";
				
			?>
			More options are available and are stored on the server when you have an account.<BR>
			<A HREF="/login">Login</A> or <A HREF="/signup">Signup</A>.<BR><BR>

			<LI><A HREF="<?= "$urlPrefix/skins" ?>">Skin Browser</A></LI>
			<LI><A HREF="<?= "$urlPrefix/profiles" ?>">Profiles</A></LI>
			<BR>

			<FORM METHOD="post" ACTION="<?= "$urlPrefix/guest/submit" ?>">
			<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>
			<TABLE WIDTH="95%" BORDER=0 ALIGN="center">

			<?
				$sql = $dbi->db->query("SELECT id,name FROM {$dbTables['skins']} WHERE active=1 ORDER BY name ASC");
				while ($sql && list($id, $name) = $dbi->db->fetch_row($sql)) {
					$skins[$id] = $name;
				}
			?>
			<TR>
				<TD>CSS (Skin)</TD>
				<TD><? $form->select("options[skin]", $skins, $skin) ?> (<A HREF="<?= "$urlPrefix/guest/set/skin/$skin" ?>">URL</A>)</TD>
			</TR>

			<TR><TD COLSPAN=2>&nbsp;</TD></TR>
			<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Save Settings"></TD></TR>
			</TABLE>
			</FORM>
			<?
			break;

		}
	}

	$nocontent = 0;
?>
