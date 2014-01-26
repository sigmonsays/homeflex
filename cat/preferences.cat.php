<?
	if (!defined("VALID")) die;

	if ($loggedIn) {
		switch ($func) {
			case "submit":
				$options = $_POST['options'];

				if (headers_sent()) {
					unset($func);
					require("cat/$cat.cat.php");
				} else {
					foreach($options AS $option => $value) {
						setOption($option, $value);
					}
					header("Location: $urlPrefix/preferences/saved");
				}
				break;

			default:
				$form = new formInput;

				$options = getOptions($_SESSION['s_id']);
				//this is kinda of sloppy =)
				@extract($options);

				echo '<H2>Preferences</H2>';
				if ($func == "saved") echo "<CENTER>Preferences updated.</CENTER><BR><BR>";
				

				?>
				<LI><A HREF="/skins">Skin Browser</A></LI>
				<LI><A HREF="/profile">My Profile</A></LI>
				<BR>

				<FORM METHOD="post" ACTION="<?= "$urlPrefix/preferences/submit" ?>">
				<INPUT TYPE="hidden" NAME="nocontent" VALUE=1>

				<DIV CLASS="row1" STYLE="margin-left: 1em; margin-right: 1em; border: 1px solid black; padding: 0.3em;"><B>Layout</B></DIV>
				<DIV CLASS="row2" STYLE="margin-left: 1em; margin-right: 1em; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; padding: 0.3em;">
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">

				<?
					$dbi->db->query(
						"SELECT id,name FROM {$dbTables['skins']} WHERE active=1 ORDER BY name ASC");

					while (list($id, $name) = $dbi->db->fetch_row()) {
						$skins[$id] = $name;
					}
				?>

				<DIV CLASS="row2">
				<TR>
					<TD>CSS (Skin) <I><?= $skin ?></I> </TD>
					<TD><? $form->select("options['skin']", $skins, $skin); ?> (<A HREF="<?= "$urlPrefix/guest/set/skin/$skin" ?>">URL</A>)</TD>
				</TR>
				</TABLE>
				</DIV>


				<BR>
				<DIV CLASS="row1" STYLE="margin-left: 1em; margin-right: 1em; border: 1px solid black; padding: 0.3em;"><B>Profile Options</B></DIV>
				<DIV CLASS="row2" STYLE="margin-left: 1em; margin-right: 1em; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; padding: 0.3em;">
				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR>
					<TD>Public Profile</TD>
					<TD><? $form->boolean("options['public_profile']", $public_profile, "Yes/No") ?></TD>
				</TR>
				</TABLE>
				</DIV>

				<TABLE WIDTH="90%" BORDER=0 ALIGN="center">
				<TR><TD COLSPAN=2>&nbsp;</TD></TR>
				<TR><TD COLSPAN=2 ALIGN="center"><INPUT TYPE="submit" VALUE="Save Settings"></TD></TR>
				</TABLE>
				<?
				break;

		}

	} else {
		div_box("Information", "You must be logged in to use this feature!");
	}

	//sometimes I wonder how I came up with these "cool code layouts", then I see myself doing something like this
	//to have the footer included.. ARGHhhh
	$nocontent = 0;
?>
